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
}
