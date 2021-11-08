<?php

namespace App\Controller;

use App\Model\ProposeManager;

class ProposeController extends AbstractController
{
    public function addPropose(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proposes = array_map('trim', $_POST['propose']);
            foreach ($proposes as $propose) {
                if (empty($propose)) {
                    $errors['empty_propose'] = 'Toutes les proposition(s) doivent être remplies';
                }
                if (strlen($propose) < 2) {
                    $errors['car_propose'] = 'Toutes les proposition(s) doivent faire plus de 2 caractères';
                }
            }
            if (empty($errors)) {
                $proposeManager = new ProposeManager();
                foreach ($proposes as $content) {
                    $proposeManager->insertPropose($content, $_POST['activityId']);
                }
                header('Location: /activite/afficher?id=' . $_GET['id']);
            }
        }
        $activityId = $_GET['id'];
        return $this->twig->render('Activity/addPropose.html.twig', ['activity_id' => $activityId,
            'errors' => $errors]);
    }
}
