<?php

namespace App\Controller;

use App\Model\RegisterManager;
use App\Service\RegisterFormValidator;

class RegisterController extends AbstractController
{
    public function add(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new RegisterFormValidator($_POST);
            $formValidator->trimAll();
            $user = $formValidator->getPosts();
            $toCheckInputs = [
                'firstname' => 'Le prénom',
                'lastname'  => 'le nom',
                'mail'      => 'le mail',
                'password'  => 'le mot de passe',
                'github'    => 'le pseudo github'
             ];
            $formValidator->checkEmptyInputs($toCheckInputs);
            $formValidator->checkLength($_POST['password'], 'le mot de passe', 6, 255);
     //       if (!filter_var($user["mail"], FILTER_VALIDATE_EMAIL)) {
      //          $errors['formatEmail'] = "Le format de l'email est invalide";
       //     }
            $formValidator->checkIfMailAlreadyExists($user['mail']);
            $errors = $formValidator->getErrors();
            if (empty($errors)) {
                $registerManager = new RegisterManager();
                $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
                $userId = $registerManager->create($user);
                $userData = $registerManager->selectOneById($userId);
                $_SESSION['register'] = $userData;
                header('Location: /userData/profil?id=' . $userId);
            }
        }
            return $this->twig->render(
                'userData/formRegister.html.twig',
                ['register_succes' => $_GET['add'] ?? null,
                    'errors' => $errors]
            );
    }

    public function connect(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            $registerManager = new RegisterManager();
            $userData = $registerManager->selectOneByEmail($user['mail']);
            if ($userData) {
                if (password_verify($user['password'], $userData['password'])) {
                    $_SESSION['register'] = $userData;
                    header('Location: /activity/showAll');
                } else {
                    $errors['idIncorrect'] = 'Vos identifiants de connexion sont incorrects';
                }
            } else {
                $errors['idIncorrect'] = 'Vos identifiants de connexion sont incorrects';
            }
        }
        return $this->twig->render('Home/index.html.twig', ['session' => $_SESSION, 'errors' => $errors]);
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
