<?php

namespace App\Core;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;

class Utils
{
    public static function view($path)
    {
        $response = new Response;
        if (file_exists(APP_ROOT . '/src/Views/' . $path . '.php')) {
            $viewpath = file_get_contents(APP_ROOT . '/src/Views/' . $path . '.php');
            return new HtmlResponse($viewpath, 200);
        } else {
            $response->getBody()->write('404 - File not found');
            return $response->withStatus(404);
        }
    }
}