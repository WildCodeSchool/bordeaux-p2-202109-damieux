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
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new FormValidator($_POST);
            $formValidator->trimAll();
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
        }
        return $this->twig->render('Activity/addActivity.html.twig', [
            'errors' => $errors
        ]);
    }

    public function show(int $activityId): string
    {
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
                //todo send mail to activity user email
                $swift = new Swift_SmtpTransport('ssl0.ovh.net', 587);
                $swift->setUsername(APP_USERNAME);
                $swift->setPassword(APP_PASSWORD);
                $mailer = new Swift_Mailer($swift);
                $message = new Swift_Message();
                $message->setFrom(['wilderevent@harari.ovh' => 'Wilder Event'])
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
        $ableToVote = !(in_array($_SESSION['register']['id'], $votingUsersId));
        $proposeVoting = null;
        if (!$ableToVote) {
            $proposeVoting = $choiceManager->showProposeVotingByUserId(
                $activityId,
                $_SESSION['register']['id']
            );
        }
        $commentManager = new CommentManager();
        $comments = $commentManager->selectUsersFirstnameByActivityId($activityId);
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
            ]);
    }

    public function showAll(): string
    {
        $activityManager = new ActivityManager();
        $activities = $activityManager->selectActivityIsActive();

        return $this->twig->render(
            'Activity/showAll.html.twig',
            ['activities' => $activities]
        );
    }

    public function addCommentByActivity($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'];
            $userId = $_SESSION['register']['id'];
            $activityId = $_GET['id'];
            $commentManager = new CommentManager();
            $commentManager->insertCommentByActivityIdAndUserId($content, $activityId, $userId);
            header('Location: /activite/afficher?id=' . $id);
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

