<?php

namespace App\Middleware;

use App\Repositories\ProductRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpNotFoundException;
class GetProduct
{

    public function __construct(private ProductRepository $repository)
    {
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();

        $id = $route->getArgument('id');

        $data = $this->repository->getById((int) $id);
        if(!$data){
            throw new HttpNotFoundException($request, message: 'No se encontro el producto solicitado');
        }

        $request = $request->withAttribute('product', $data);
        return $handler->handle($request);
    }

}