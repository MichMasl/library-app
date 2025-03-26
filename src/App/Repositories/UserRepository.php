<?php

namespace App\Repositories;

use App\Database\Database;
use PDO;
use PDOException;

readonly class UserRepository
{
    public function __construct(private Database $dataBase)
    {
    }

    public function addUser(array $user): bool|string
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("INSERT INTO users (login, password_hash, api_key) VALUES (:login, :password_hash, :api_key)");
        $stmt->bindValue(':login', $user['login']);
        $stmt->bindValue(':password_hash', $user['password_hash']);
        $stmt->bindValue(':api_key', $user['api_key']);
        try {
            $stmt->execute();
        } catch (PDOException) {
            return false;
        }
        return $connection->lastInsertId();
    }

    public function getUser(string $login): array|bool
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("SELECT login FROM users WHERE login = :login");
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserPasswordHash(string $login): array
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("SELECT password_hash FROM users WHERE login = :login");
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserApiKeyByLogin(string $login): array
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("SELECT api_key FROM users WHERE login = :login");
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserIdByApiKey(string $apiKey): array
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("SELECT user_id, login FROM users WHERE api_key = :api_key");
        $stmt->bindValue(':api_key', $apiKey);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findApiKey(string $apiKey): array|bool
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("SELECT api_key FROM users WHERE api_key = :api_key");
        $stmt->bindValue(':api_key', $apiKey);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers(): array
    {
        $connection = $this->dataBase->getConnection();
        $stmt = $connection->prepare("SELECT user_id, login FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
