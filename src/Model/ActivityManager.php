<?php

namespace App\Model;

class ActivityManager extends AbstractManager
{
    public const TABLE = 'activity';

    public function insert(array $activities): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "
        (title, description, created_at) 
         VALUES (:title, :description, now())");
        $statement->bindValue(':title', $activities['title'], \PDO::PARAM_STR);
        $statement->bindValue(':description', $activities['description'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
