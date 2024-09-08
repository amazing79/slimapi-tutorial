<?php

use App\Database;

return [
    Database::class => function () {
        return new Database(
            host: $_ENV['DATABASE_HOST'],
            db: $_ENV['DATABASE_NAME'],
            user: $_ENV['DATABASE_USER'],
            password:$_ENV['DATABASE_PASSWORD']
        );
    }
];