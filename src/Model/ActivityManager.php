<?php

namespace App\Model;

class ActivityManager extends AbstractManager
{
    public const TABLE = 'activity';

    public function insert(array $activities): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "
        (title, description, created_at, user_id, is_active) 
         VALUES (:title, :description, now(), :user_id, true)");
        $statement->bindValue(':title', $activities['title'], \PDO::PARAM_STR);
        $statement->bindValue(':description', $activities['description'], \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $activities['user_id'], \PDO::PARAM_INT);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function getActivitiesFromUserId(int $userId): array
    {
        $query = 'SELECT * FROM activity where user_id = :userId';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectActivityIsActive(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM activity WHERE is_active = true';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        return $this->pdo->query($query)->fetchAll();
    }

    public function updateActivityIsActive($activityId): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
            " SET is_active = false WHERE id = :id ");
        $statement->bindValue('id', $activityId, \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function getActivityWithMail(int $activityId): array
    {
        $statement = $this->pdo->prepare("SELECT activity.*, user.mail FROM activity 
        JOIN user ON user.id=activity.user_id
        WHERE activity.id=:activityId");
        $statement->bindvalue(':activityId', $activityId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();

    }
}
