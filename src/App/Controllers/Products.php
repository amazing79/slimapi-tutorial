<?php

namespace App\Controllers;

use App\Repositories\ProductRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Products
{

    public function __construct(private ProductRepository $repository)
    {
    }

    public function show(Request $request, Response $response, string $id): Response
    {
        $product = $request->getAttribute('product');
        $body = json_encode($product);
        $response->getBody()->write($body);
        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
       $body= $request->getParsedBody();
       $id = $this->repository->create($body);
       $body = json_encode([
           'message' => 'El producto se ha registrado exitosamente',
           'id' => $id
       ]);
       $response->getBody()->write($body);
       return $response->withStatus(201);
    }
}