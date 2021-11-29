<?php

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\ChoiceManager;
use App\Model\CommentManager;
use App\Model\ProposeManager;
use App\Model\RegisterManager;
use App\Service\FormValidator;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class ActivityController extends AbstractController
{
    public function add(): string
    {
        if (!isset($_SESSION['register'])) {
            header('Location: /');
        }
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new FormValidator($_POST);
            $formValidator->trimAllAndUcfirt();
            $toCheckInputs = [
                'title' => 'Le titre',
                'description' => 'La description'
            ];
            $formValidator->checkEmptyInputs($toCheckInputs);
            $formValidator->checkLength($_POST['title'], 'Le titre', 2, 50);
            $formValidator->checkLength($_POST['description'], 'La description', 2, 2500);
            $errors = $formValidator->getErrors();
            $activities = $formValidator->getPosts();
            $activities['user_id'] = $_SESSION['register']['id'];
            if (empty($errors)) {
                $activityManager = new ActivityManager();
                $id = $activityManager->insert($activities);
                header('Location: /activite/ajout-proposition?id=' . $id);
            }
            $swift = new Swift_SmtpTransport('ssl0.ovh.net', 587);
            $swift->setUsername(APP_USERNAME);
            $swift->setPassword(APP_PASSWORD);
            $mailer = new Swift_Mailer($swift);
            $message = new Swift_Message();
            $message->setSubject('Nouvelle activité sur WilderEvent')
                ->setFrom(['wilderevent@harari.ovh' => 'Wilder Event'])
                ->setTo(['wilderevent33@gmail.com'])
                ->setBody('Une nouvelle activité a été créée');
            $mailer->send($message);
        }

        return $this->twig->render('Activity/addActivity.html.twig', [
            'errors' => $errors
        ]);
    }

    public function show(int $activityId): string
    {
        if (!isset($_SESSION['register'])) {
            header('Location: /');
        }
        $activityManager = new ActivityManager();
        $activity = $activityManager->getActivityWithMail($activityId);
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['answers'])) {
                $errors[] = 'La sélection ne peut être vide';
            }
            if (empty($errors)) {
                foreach ($_POST['answers'] as $answer) {
                    $userId = $_SESSION['register']['id'];
                    $choiceManager = new ChoiceManager();
                    $choiceManager->insertChoice($answer, $userId);
                }
                $swift = new Swift_SmtpTransport('ssl0.ovh.net', 587);
                $swift->setUsername(APP_USERNAME);
                $swift->setPassword(APP_PASSWORD);
                $mailer = new Swift_Mailer($swift);
                $message = new Swift_Message();
                $message->setSubject('Réponse à votre sondage')
                    ->setFrom(['wilderevent@harari.ovh' => 'Wilder Event'])
                    ->setTo([$activity['mail']])
                    ->setBody('Un participant a répondu à votre sondage : ' . $activity['title']);
                $mailer->send($message);
            }
        }
        $proposeManager = new ProposeManager();
        $registerManager = new RegisterManager();
        $creatorIid = $activity['user_id'];
        $proposes = $proposeManager->selectProposesByActivityId($activityId);
        $userData = $registerManager->selectOneById($creatorIid);
        $choiceManager = new ChoiceManager();
        $chartProposes = [];
        $voteCountByAnswer = [];
        foreach ($proposes as $propose) {
            $chartProposes[] = $propose['content'];
            $voteCountByAnswer[] = $choiceManager->countVoteByProposition($propose['id'])['count'];
        }
        $votingUsersId = $choiceManager->selectVotingUsersIdsByActivityId($activityId);
        $userNameByVotings = $choiceManager->showVotingUserByProposeId($activityId);
        $ableToVote = !(in_array($_SESSION['register']['id'], $votingUsersId));
        $proposeVoting = null;
        if (!$ableToVote) {
            $proposeVoting = $choiceManager->showProposeVotingByUserId(
                $activityId,
                $_SESSION['register']['id']
            );
        }
        $commentErrors = [];
        foreach ($_GET as $key => $value) {
            if ($key !== 'id') {
                $commentErrors[] = $value;
            }
        }
        $commentManager = new CommentManager();
        $comments = $commentManager->selectUsersFirstnameByActivityId($activityId);
        $sortedUsers = [];
        foreach ($userNameByVotings as $userVoteData) {
            $content = $userVoteData['content'];
            $sortedUsers[$content][] = $userVoteData;
        }

        return $this->twig->render('Activity/show.html.twig', [
            'activity' => $activity,
            'proposes' => $proposes,
            'user_data' => $userData,
            'chart_proposes' => $chartProposes,
            'vote_count_by_answer' => $voteCountByAnswer,
            'errors' => $errors,
            'ableToVote' => $ableToVote,
            'proposeVoting' => $proposeVoting,
            'comments' => $comments,
            'comment_errors' => $commentErrors,
            'users_by_voting' => $sortedUsers
        ]);
    }

    public function showAll(): string
    {
        if (!isset($_SESSION['register'])) {
            header('Location: /');
        }
        $activityManager = new ActivityManager();
        $activities = $activityManager->selectActivityIsActive();
        return $this->twig->render(
            'Activity/showAll.html.twig',
            ['activities' => $activities]
        );
    }

    public function addCommentByActivity($id)
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new FormValidator($_POST);
            $formValidator->trimAllAndUcfirt();
            $toCheckInputs = [
                'content' => 'Le commentaire',
            ];
            $formValidator->checkEmptyInputs($toCheckInputs);
            $content = $formValidator->getPosts();
            $errors = $formValidator->getErrors();
            $userId = $_SESSION['register']['id'];
            $activityId = $_GET['id'];

            if (empty($errors)) {
                $commentManager = new CommentManager();
                $commentManager->insertCommentByActivityIdAndUserId($content, $activityId, $userId);
                header('Location:/activite/afficher?id=' . $id);
            } else {
                $queryString = http_build_query($errors);
                header('Location:/activite/afficher?id=' . $id . '&' . $queryString);
            }
        }
    }

    public function updateActivityByIsActive()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activityManager = new ActivityManager();
            $activityManager->updateActivityIsActive((int)$_POST['id']);
            header('Location:/activite/tout-afficher');
        }
    }
}
