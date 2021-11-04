<?php

namespace App\Model;

class RegisterManager extends AbstractManager
{
    public const TABLE = 'user';

    public function create(array $userData): int
    {
        $statement = $this->pdo->prepare('
        INSERT INTO user (firstname, lastname, mail, github, password, created_at) 
        VALUES (:firstname, :lastname, :mail, :github, :password, NOW())');
        $statement->bindValue(':firstname', $userData['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $userData['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('mail', $userData['mail'], \PDO::PARAM_STR);
        $statement->bindValue('github', $userData['github'], \PDO::PARAM_STR);
        $statement->bindValue('password', $userData['password'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }


    public function selectOneByEmail(string $mail)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE mail=:mail");
        $statement->bindValue('mail', $mail, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
