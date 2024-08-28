<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/v1/products', function (Request $request, Response $response) {
    $dsn = "mysql:host=localhost;dbname=slimapi;charset=utf8";
    $pdo = new PDO($dsn, 'slimapi', 'slimapi', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $stmt = $pdo->query('Select * from products');
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $products = json_encode($data);

    $response->getBody()->write($products);
    return $response->withHeader('Content-type', 'application/json');
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Main Page');
    return $response;
});

$app->run();