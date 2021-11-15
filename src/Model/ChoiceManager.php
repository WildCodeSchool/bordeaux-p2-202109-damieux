<?php

namespace App\Model;

class ChoiceManager extends AbstractManager
{
    public const TABLE = 'choice';

    public function countVoteByProposition(int $propositionId): array
    {
        $query = 'SELECT COUNT(proposition_id) as count FROM choice WHERE proposition_id=:proposition_id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':proposition_id', $propositionId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function insertChoice(int $propositionId, int $userId): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            "(proposition_id, user_id)
             VALUES (:proposition_id, :user_id)");
        $statement->bindValue(':proposition_id', $propositionId, \PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectVotingUsersIdsByActivityId(int $activityId): array
    {
        $statement = $this->pdo->prepare("SELECT c.user_id FROM choice c 
            JOIN proposition p ON p.id=c.proposition_id
            JOIN activity a ON a.id=p.activity_id
            WHERE a.id=:activity_id");
        $statement->bindValue(':activity_id', $activityId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function showProposeVotingByUserId(int $activityId, int $userId): array
    {
        $statement = $this->pdo->prepare("SELECT c.user_id, p.content FROM choice c 
            JOIN proposition p ON p.id=c.proposition_id
            JOIN activity a ON a.id=p.activity_id
            WHERE a.id=:activity_id
            AND c.user_id=:user_id");
        $statement->bindValue(':activity_id', $activityId, \PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
