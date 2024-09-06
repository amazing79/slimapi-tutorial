<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\ProductRepository;

class ProductIndex
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $products = json_encode($this->repository->getAll());

        $response->getBody()->write($products);
        return $response;
    }
}