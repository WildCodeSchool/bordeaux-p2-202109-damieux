<?php

namespace App\Service;

use App\Model\RegisterManager;

class RegisterFormValidator extends FormValidator
{
    public function trimAll(): void
    {
        foreach ($this->posts as $key => $input) {
            if (is_string($input)) {
                $this->posts[$key] = trim($input);
            }
        }
    }
    public function checkIfMailAlreadyExists(string $mail): void
    {
        $registerManager = new RegisterManager();
        $userData = $registerManager->selectOneByEmail($mail);
        if ($userData) {
            $this->errors[] = 'L\'email ' . $mail . ' est deja utilisé';
        }
    }
}
