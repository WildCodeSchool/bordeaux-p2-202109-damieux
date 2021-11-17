<?php

namespace App\Model;

class CommentManager extends AbstractManager
{
    public const TABLE = 'comment';

    public function insertCommentByActivityIdAndUserId(string $content, int $activityId, int $userId): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "
        (content, activity_id, user_id, created_at) 
         VALUES (:content, :activity_id, :user_id, now())");
        $statement->bindValue(':content', $content, \PDO::PARAM_STR);
        $statement->bindValue(':activity_id', $activityId, \PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectUsersFirstnameByActivityId($activityId): array
    {
        $statement = $this->pdo->prepare("SELECT c.*, u.firstname, u.github FROM comment c
            JOIN user u
            ON u.id = c.user_id
            WHERE c.activity_id=:activity_id
            ORDER BY created_at DESC");
        $statement->bindValue(':activity_id', $activityId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
