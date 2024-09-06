<?php

declare(strict_types=1);


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

$app->addBodyParsingMiddleware();

$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');
$app->add(new AddJsonResponseHeader());

$app->get('/api/v1/products', \App\Controllers\ProductIndex::class);

$app->get('/api/v1/products/{id:[0-9]+}', \App\Controllers\Products::class . ':show')
    ->add(\App\Middleware\GetProduct::class);
$app->post('/api/v1/products', [\App\Controllers\Products::class,'create']);

/*
 * codigo anterior, antes de llevar al controller
$app->get('/api/v1/products/{id:[0-9]+}', function (Request $request, Response $response, string $id) {
    $product = $request->getAttribute('product');
    $body = json_encode($product);
    $response->getBody()->write($body);
    return $response;

})->add(\App\Middleware\GetProduct::class);
*/
$app->get('/', \App\Controllers\Index::class.':index');

$app->run();