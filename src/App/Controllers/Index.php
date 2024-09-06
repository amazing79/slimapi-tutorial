<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
class Index
{
    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write('Main Page');
        return $response;
    }

}