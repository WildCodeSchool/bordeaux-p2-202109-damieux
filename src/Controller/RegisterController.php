<?php

namespace App\Controller;

use App\Model\RegisterManager;

class RegisterController extends AbstractController
{
    public function add(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            if (empty($user['firstname'])) {
                $errors['firstnameError'] = 'Le champ prénom doit être rempli';
            }
            if (empty($user['lastname'])) {
                $errors['lastnameError'] = 'Le champ nom doit être rempli';
            }
            if (empty($user['mail'])) {
                $errors['mailError'] = 'Le champ mail doit être rempli';
            }
            if (!filter_var($user["mail"], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'email est invalide";
            }
            if (empty($user['password'])) {
                $errors['passwordError'] = 'Le champ mot de passe doit être rempli';
            }
            if (strlen($user['password']) < 2) {
                $errors[] = "Le mot de passe doit faire plus de 2 caractères";
            }
            $registerManager = new RegisterManager();
            $userData = $registerManager->selectOneByEmail($user['mail']);
            if ($userData !== false) {
                $errors['mailDouble'] = "L'email est déjà utilisé";
            }
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
            if ($userData !== false) {
                if (password_verify($user['password'], $userData['password'])) {
                    $_SESSION['register'] = $userData;
                    header('Location: /activity/addActivity');
                } else {
                    $errors['idIncorrect'] = 'Vos identifiants sont incorrects';
                }
            } else {
                $errors['idIncorrect'] = 'Vos identifiants sont incorrects';
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
