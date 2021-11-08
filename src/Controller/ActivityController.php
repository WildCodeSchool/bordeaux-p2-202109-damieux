<?php

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\ProposeManager;
use App\Model\RegisterManager;

class ActivityController extends AbstractController
{
    public function add(): string
    {
        $errors = [];
        $userId = $_SESSION['register']['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activities = array_map('trim', $_POST);
            $activities['user_id'] = $userId;
            if (empty($activities['title'])) {
                $errors['empty_title'] = 'Le titre doit être rempli';
            }
            if (empty($activities['description'])) {
                $errors['description_vide'] = 'La description doit être remplie';
            }
            if (strlen($activities['title']) < 2) {
                $errors['title_car'] = 'Le titre doit contenir plus de 2 caractères';
            }
            if (strlen($activities['description']) < 2) {
                $errors['description_car'] = 'La description doit contenir plus de 2 caractères';
            }
            if (empty($errors)) {
                $activityManager = new ActivityManager();
                $id = $activityManager->insert($activities);
                header('Location: /activity/addPropose?id=' . $id);
            }
        }
        return $this->twig->render('Activity/addActivity.html.twig', ['errors' => $errors]);
    }

    public function show(int $activityId): string
    {
        $activityManager = new ActivityManager();
        $proposeManager = new ProposeManager();
        $registerManager = new RegisterManager();
        $activity = $activityManager->selectOneById($activityId);
        $proposes = $proposeManager->selectProposesByActivityId($activityId);
        $userData = $registerManager->selectOneById($activityId);

        return $this->twig->render(
            'Activity/show.html.twig',
            ['activity' => $activity,
                'proposes' => $proposes,
                'user_data' => $userData]
        );
    }

    // AFFICHAGE DE TOUTES LES ACTIVITES (TITRE + DESCRIPTION)
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
