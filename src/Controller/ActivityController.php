<?php

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\ProposeManager;

class ActivityController extends AbstractController
{
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activity = array_map('trim', $_POST);

            // TODO validations (length, format...)

            $activityManager = new ActivityManager();
            $id = $activityManager->insert($activity);
            header('Location: /activity/addPropose?id=' . $id);
        }
        return $this->twig->render('Activity/addActivity.html.twig');
    }

    public function show(int $activityId): string
    {
        $activityManager = new ActivityManager();
        $proposeManager = new ProposeManager();
        $activity = $activityManager->selectOneById($activityId);
        $proposes = $proposeManager->selectProposeByActivityId($activityId);

        return $this->twig->render('Activity/show.html.twig', ['activity' => $activity, 'proposes' => $proposes]);
    }
}
