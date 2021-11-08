<?php

namespace App\Service;

use App\Model\RegisterManager;

class RegisterFormValidator extends FormValidator
{
    public function checkIfMailAlreadyExists(string $mail): void
    {
        $registerManager = new RegisterManager();
        $userData = $registerManager->selectOneByEmail($mail);
        if ($userData) {
            $this->errors[] = 'L\'email ' . $mail . ' est deja utilisé';
        }
    }
}
