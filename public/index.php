<?php

declare(strict_types=1);

use App\Repositories\ProductRepository;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/v1/products', function (Request $request, Response $response) {

    $repository = new ProductRepository();
    $products = json_encode($repository->getAll());

    $response->getBody()->write($products);
    return $response->withHeader('Content-type', 'application/json');
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Main Page');
    return $response;
});

$app->run();