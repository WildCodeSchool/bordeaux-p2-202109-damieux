<?php

namespace App\Model;

class ChoiceManager extends AbstractManager
{
    public const TABLE = 'choice';

    public function countVoteByProposition(int $propositionId): array
    {
        $query = 'SELECT COUNT(proposition_id) as count FROM damieux.choice WHERE proposition_id=:proposition_id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':proposition_id', $propositionId);
        $statement->execute();
        return $statement->fetch();
    }
}
