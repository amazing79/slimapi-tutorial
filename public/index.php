<?php

declare(strict_types=1);

use App\Repositories\ProductRepository;

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DI\ContainerBuilder;

define ('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')
            ->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->get('/api/v1/products', function (Request $request, Response $response) {

    //$dataBase = $this->get(App\Database::class);
    //$repository = new ProductRepository($dataBase);
    $repository = $this->get(ProductRepository::class);
    $products = json_encode($repository->getAll());

    $response->getBody()->write($products);
    return $response->withHeader('Content-type', 'application/json');
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Main Page');
    return $response;
});

$app->run();