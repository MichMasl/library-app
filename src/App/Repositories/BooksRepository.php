<?php

namespace App\Repositories;

use App\Database\Database;
use PDO;

readonly class BooksRepository
{
    public function __construct(private Database $dataBase)
    {
    }

    public function getAll(string $userId): array
    {
        $connection = $this->dataBase->getConnection();
        $sql = "SELECT books.book_id, books.name 
                FROM books 
                JOIN users_books ON books.book_id = users_books.book_id 
                WHERE users_books.user_id = :user_id
                AND del = 0";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookById(string $userId, string $bookId): array|bool
    {
        $connection = $this->dataBase->getConnection();
        $sql = "SELECT books.name, books.text
                FROM books
                JOIN users_books ON books.book_id = users_books.book_id
                WHERE users_books.user_id = :user_id AND users_books.book_id = :book_id
                AND del = 0";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':book_id', $bookId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addBook(string $bookName, string $bookText): int
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("INSERT INTO books (name, text) VALUES (:name, :text)");
        $stmt->bindValue(':name', $bookName);
        $stmt->bindValue(':text', $bookText);
        $stmt->execute();
        return $connection->lastInsertId();
    }

    public function updateBook(array $data): bool
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("UPDATE books SET name = :name, text = :text WHERE book_id = :book_id");
        $stmt->bindValue(':name', $data['bookName']);
        $stmt->bindValue(':text', $data['text']);
        $stmt->bindValue(':book_id', $data['bookId']);
        return $stmt->execute();
    }

    public function deleteBook(string $userId, string $bookId): bool
    {
        $connection = $this->dataBase->getConnection();
        $sql = "UPDATE books
                JOIN users_books ON books.book_id = users_books.book_id
                SET books.del = 1
                WHERE users_books.book_id = :book_id
                AND users_books.user_id = :user_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':book_id', $bookId);
        $stmt->bindValue(':user_id', $userId);
        return $stmt->execute();
    }
}
