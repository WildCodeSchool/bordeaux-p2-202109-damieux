<?php

namespace App\Model;

class ActivityManager extends AbstractManager
{
    public const TABLE = 'activity';

    public function insert(array $activity): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "
        (title, description, created_at) 
         VALUES (:title, :description, now())");

        $statement->bindValue(':title', $activity['title'], \PDO::PARAM_STR);
        $statement->bindValue(':description', $activity['description'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
