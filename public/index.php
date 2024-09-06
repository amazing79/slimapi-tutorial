<?php

declare(strict_types=1);

use App\Controllers\ProductIndex;
use App\Controllers\Products;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\AddJsonResponseHeader;
use Slim\Routing\RouteCollectorProxy;

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

$app->group('/api/v1', function (RouteCollectorProxy $group)
{
    $group->get('/products', ProductIndex::class);
    $group->post('/products', [Products::class,'create']);

    $group->group('', function (RouteCollectorProxy $group) {
        $group->get('/products/{id:[0-9]+}', Products::class . ':show');
        $group->patch('/products/{id:[0-9]+}', [Products::class,'update']);
        $group->delete('/products/{id:[0-9]+}', [Products::class,'delete']);
    })->add(\App\Middleware\GetProduct::class);
});

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