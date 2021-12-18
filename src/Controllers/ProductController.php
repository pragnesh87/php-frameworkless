<?php

namespace App\Controllers;

use App\Core\Utils;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;

class ProductController
{
    public function index(ServerRequestInterface $request)
    {
        return Utils::view('index');
    }

    public function data()
    {
        $response = new Response;
        $data = [
            'abc' => 1,
            'xyz' => 10
        ];
        $response->getBody()->write(json_encode(['status' => 200, 'data' => $data]));
        return $response->withStatus(200);
    }
}