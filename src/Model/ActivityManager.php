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

    public function insertPropose(string $content, int $activityId): void
    {
        $statement = $this->pdo->prepare("INSERT INTO proposition
        (content, created_at, activity_id) VALUES (:content, now(), :activity_id)");
        $statement->bindValue(':content', $content, \PDO::PARAM_STR);
        $statement->bindValue(':activity_id', $activityId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectProposeByActivityId(int $activityId): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM proposition WHERE activity_id=:activityId");
        $statement->bindValue('activityId', $activityId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
