<?php

namespace App\Controller;

use App\Model\RegisterManager;

class CommunityController extends AbstractController
{
    public function showUser()
    {
        if (!isset($_SESSION['register'])) {
            header('Location: /');
        }
        $registerManager = new RegisterManager();
        $usersData = $registerManager->selectAll();
        return $this->twig->render('userData/communaute.html.twig', ['usersData' => $usersData]);
    }
}
