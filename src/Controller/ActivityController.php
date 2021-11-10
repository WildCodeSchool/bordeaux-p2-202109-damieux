<?php

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\ChoiceManager;
use App\Model\ProposeManager;
use App\Model\RegisterManager;
use App\Service\FormValidator;

class ActivityController extends AbstractController
{
    public function add(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new FormValidator($_POST);
            $formValidator->trimAll();
            $toCheckInputs = [
                'title'       => 'Le titre',
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['answers'] as $answer) {
                $userId = $_SESSION['register']['id'];
                $choiceManager = new ChoiceManager();
                $choiceManager->insertChoice($answer, $userId);
            }
        }
        $activityManager = new ActivityManager();
        $proposeManager = new ProposeManager();
        $registerManager = new RegisterManager();
        $activity = $activityManager->selectOneById($activityId);
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
        return $this->twig->render('Activity/show.html.twig', [
                'activity' => $activity,
                'proposes' => $proposes,
                'user_data' => $userData,
                'chart_proposes' => $chartProposes,
                'vote_count_by_answer' => $voteCountByAnswer,
            ]);
    }

    public function showAll(): string
    {
        $activityManager = new ActivityManager();
        $activities = $activityManager->selectAll();

        return $this->twig->render(
            'Activity/showAll.html.twig',
            ['activities' => $activities]
        );
    }
}
