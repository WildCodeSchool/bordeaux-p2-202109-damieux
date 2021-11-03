<?php

namespace App\Controller;

use App\Model\ProposeManager;

class ProposeController extends AbstractController
{
    public function addPropose(): string
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
            $proposes = array_map('trim', $_POST['propose']);

            // TODO validations (length, format...)

            $proposeManager = new ProposeManager();
            foreach ($proposes as $content) {
                $proposeManager->insertPropose($content, $_POST['activityId']);
            }

            header('Location: /activity/show?id=' . $_GET['id']);
        }
        $activityId = $_GET['id'];
        return $this->twig->render('Activity/addPropose.html.twig', ['activity_id' => $activityId]);
    }
}
