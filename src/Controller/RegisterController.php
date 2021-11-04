<?php

namespace App\Controller;

use App\Model\RegisterManager;

class RegisterController extends AbstractController
{
    public function add(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['firstname'])) {
                $errors['FirstnameError'] = "le champs prénom doit etre remplie .";
            }
            if (empty($_POST['lastname'])) {
                $errors['LastnameError'] = "le champs nom doit etre remplie.";
            }
            if (empty($_POST['mail'])) {
                $errors['MailError'] = "le champs mail doit etre remplie.";
            }

            if (empty($_POST['password'])) {
                $errors['PasswordError'] = "le champs mot de passe  doit etre remplie.";
            }


            if (empty($errors)) {
                $registerManager = new RegisterManager();
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $userId = $registerManager->create($_POST);
                $userData = $registerManager->selectOneById($userId);
                $_SESSION['user'] = $userData;
                header('Location: /userData/profil?id=' . $userId);
            }
        }
        return $this->twig->render(
            'userData/formRegister.html.twig',
            ['register_succes' => $_GET['add'] ?? null, 'errors' => $errors]
        );
    }

    public function connect(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registerManager = new RegisterManager();
            $userData = $registerManager->selectOneByEmail($_POST['mail']);
            if (password_verify($_POST['password'], $userData['password'])) {
                $_SESSION['register'] = $userData;
            } else {
                var_dump('not ok');
            }
        }
        return $this->twig->render('Home/index.html.twig', ['session' => $_SESSION,]);
    }

    public function logout()
    {
        session_destroy();
        header('location: /');
    }

    public function profil(int $id): string
    {
        $registerManager = new RegisterManager();
        $userData = $registerManager->selectOneById($id);
        return $this->twig->render('userData/profil.html.twig', [
            'user_data' => $userData
        ]);
    }
}
