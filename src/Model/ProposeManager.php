<?php

namespace App\Model;

class ProposeManager extends AbstractManager
{
    public const TABLE = 'proposition';

    public function insertPropose(string $content, int $activityId): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "
        (content, created_at, activity_id) VALUES (:content, now(), :activity_id)");
        $statement->bindValue(':content', $content, \PDO::PARAM_STR);
        $statement->bindValue(':activity_id', $activityId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectProposesByActivityId(int $activityId): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE activity_id=:activityId");
        $statement->bindValue('activityId', $activityId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
