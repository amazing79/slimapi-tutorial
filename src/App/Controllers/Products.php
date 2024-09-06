<?php

namespace App\Controllers;

use App\Repositories\ProductRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Products
{
    public function show(Request $request, Response $response, string $id): Response
    {
        $product = $request->getAttribute('product');
        $body = json_encode($product);
        $response->getBody()->write($body);
        return $response;
    }
}