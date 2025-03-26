<?php

use App\Database\Database;

return [
    Database::class => function () {
        return new Database(
            host: $_ENV['DB_HOST'],
            dbname: $_ENV['DB_NAME'],
            username: $_ENV['DB_USERNAME'],
            password: null
        );
    }
];
