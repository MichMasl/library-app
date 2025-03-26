<?php

namespace App\Repositories;

use App\Database\Database;
use PDO;
use PDOException;

readonly class UsersBooksAccessRepository
{
    public function __construct(private Database $database)
    {
    }

    public function grandAccess(string $userId, string $viewerId): bool
    {
        $connection = $this->database->getConnection();
        $stmt = $connection->prepare("INSERT INTO users_books_access (user_id, viewer_id) VALUES (:user_id, :viewer_id)");
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':viewer_id', $viewerId);
        try {
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }

    }

    public function checkAccess(string $userId, string $viewerId): array|bool
    {
        $connection = $this->database->getConnection();
        $stmt = $connection->prepare("SELECT user_id, viewer_id FROM users_books_access WHERE user_id = :user_id AND viewer_id = :viewer_id");
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':viewer_id', $viewerId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
