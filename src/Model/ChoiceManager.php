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

    public function insertChoice(array $propositionId): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            "(proposition_id, user_id)
             VALUES (:proposition_id, :user_id)");
        $statement->bindValue(':proposition_id', $propositionId['proposition_id'], \PDO::PARAM_INT);
        $statement->bindValue(':user_id', $propositionId['user_id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
