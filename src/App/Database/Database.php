<?php

namespace App\Database;

use PDO;

readonly class Database
{
    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private ?string $password
    ) {
    }

    public function getConnection(): PDO
    {
        return new PDO(
            dsn: "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
            username: $this->username,
            password: $this->password,
            options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
