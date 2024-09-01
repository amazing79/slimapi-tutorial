<?php

use App\Database;

return [
    Database::class => function () {
        return new Database(
            host: 'localhost',
            db: 'slimapi',
            user: 'slimapi',
            password:'slimapi'
        );
    }
];