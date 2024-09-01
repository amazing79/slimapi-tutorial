<?php

declare(strict_types=1);

use App\Repositories\ProductRepository;

use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\AddJsonResponseHeader;

define ('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')
            ->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$collector = $app->getRouteCollector();

$collector->setDefaultInvocationStrategy(new RequestResponseArgs());

$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');
$app->add(new AddJsonResponseHeader());

$app->get('/api/v1/products', function (Request $request, Response $response) {

    //$dataBase = $this->get(App\Database::class);
    //$repository = new ProductRepository($dataBase);
    $repository = $this->get(ProductRepository::class);
    $products = json_encode($repository->getAll());

    $response->getBody()->write($products);
    return $response;
});

$app->get('/api/v1/products/{id:[0-9]+}', function (Request $request, Response $response, string $id) {

    $repository = $this->get(ProductRepository::class);

    $data = json_encode($repository->getById((int) $id));
    if(!$data){
        throw new HttpNotFoundException($request, message: 'No se encontro el producto solicitado');
    }
    $response->getBody()->write($data);
    return $response;
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Main Page');
    return $response;
});

$app->run();