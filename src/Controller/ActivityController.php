<?php

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\ProposeManager;

class ActivityController extends AbstractController
{
    public function add(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activities = array_map('trim', $_POST);
            if (empty($activities['title'])) {
                $errors['empty_title'] = 'Le titre doit être remplie';
            }
            if (empty($activities['description'])) {
                $errors['description_vide'] = 'La description doit être remplie';
            }
            if (strlen($activities['title']) < 2) {
                $errors['title_car'] = 'Le titre doit faire plus de 2 caractéres';
            }
            if (strlen($activities['description']) < 2) {
                $errors['description_car'] = 'La description doit faire plus de 2 caractéres';
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
        $activity = $activityManager->selectOneById($activityId);
        $proposes = $proposeManager->selectProposesByActivityId($activityId);

        return $this->twig->render('Activity/show.html.twig', ['activity' => $activity, 'proposes' => $proposes]);
    }
}
