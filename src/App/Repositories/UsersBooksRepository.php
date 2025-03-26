<?php

namespace App\Repositories;

use App\Database\Database;
use PDOException;

readonly class UsersBooksRepository
{
    public function __construct(private Database $database)
    {
    }

    public function addBookToUser(string $userId, string $bookId): bool
    {
        $connection = $this->database->getConnection();
        $stmt = $connection->prepare("INSERT INTO users_books (user_id, book_id) VALUES (:user_id, :book_id)");
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':book_id', $bookId);
        try {
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
