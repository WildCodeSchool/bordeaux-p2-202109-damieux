<?php

namespace App\Controller;

use App\Model\ProposeManager;
use App\Service\ProposeValidator;

class ProposeController extends AbstractController
{
    public function addPropose(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new ProposeValidator($_POST);
            $formValidator->cleanProposes();
            $proposes = $formValidator->getPosts();
            $errors = $formValidator->getErrors();
            if (empty($errors)) {
                $proposeManager = new ProposeManager();
                foreach ($proposes['propose'] as $content) {
                    $proposeManager->insertPropose($content, $_POST['activityId']);
                }
                header('Location: /activity/show?id=' . $_GET['id']);
            }
        }
        $activityId = $_GET['id'];
        return $this->twig->render('Activity/addPropose.html.twig', ['activity_id' => $activityId,
            'errors' => $errors]);
    }
}
