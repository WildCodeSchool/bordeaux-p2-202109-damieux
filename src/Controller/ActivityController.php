<?php

namespace App\Controller;

use App\Model\ActivityManager;

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

    public function addPropose(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           // var_dump($_POST);die;
            $proposes = array_map('trim', $_POST['propose']);

            // TODO validations (length, format...)

            $activityManager = new ActivityManager();
            foreach ($proposes as $content) {
                $activityManager->insertPropose($content, $_GET['id']);
            }
            header('Location: /activity/show?id=' . $_GET['id']);
        }
        return $this->twig->render('Activity/addPropose.html.twig');
    }

    public function show(int $activityId): string
    {
        $activityManager = new ActivityManager();
        $activity = $activityManager->selectOneById($activityId);
        $proposes = $activityManager->selectProposeByActivityId($activityId);

        return $this->twig->render('activity/show.html.twig', ['activity' => $activity, 'proposes' => $proposes]);
    }
}
