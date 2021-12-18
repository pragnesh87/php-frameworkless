<?php

namespace App\Core;

use Laminas\Diactoros\Response;

class Utils
{
    public static function view($path)
    {
        $response = new Response;
        if (file_exists(APP_ROOT . '/src/Views/' . $path . '.php')) {
            $viewpath = APP_ROOT . '/src/Views/' . $path . '.php';
            $response->getBody()->write(file_get_contents($viewpath));
            return $response->withStatus(200);
        } else {
            $response->getBody()->write('404 - File not found');
            return $response->withStatus(404);
        }
    }
}