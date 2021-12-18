<?php

declare(strict_types=1);

use Whoops\Run;
use Dotenv\Dotenv;
use Monolog\Logger;
use League\Route\Router;
use League\Container\Container;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Whoops\Handler\PrettyPageHandler;
use App\Controllers\ProductController;
use Laminas\Diactoros\ResponseFactory;
use League\Route\Strategy\JsonStrategy;
use League\Container\ReflectionContainer;
use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Strategy\ApplicationStrategy;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

require '../vendor/autoload.php';

/*
 * Dotenv initialization
 */
if (file_exists(__DIR__ . '/../.env') !== true) {
    echo 'Missing .env file (please copy .env.example).';
    return;
}

$dotenv = Dotenv::createMutable(__DIR__ . '/../');
$dotenv->load();

/*
 * Create request instance
 */
$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

/*
 * Setup logger
 */
$logger = new Logger('MyApp');
$formatter = new LineFormatter();
$handler = new StreamHandler("../logs/error.log");
$handler->setFormatter($formatter);
$logger->pushHandler($handler);

/*
 * Error handler
 */
$whoops = new Run;
if ($_ENV['APP_ENV'] === 'dev') {
    $whoops->pushHandler(
        new PrettyPageHandler()
    );
} else {
    $whoops->pushHandler(function ($exception, $inspector, $run) use ($logger) {
        $logger->error($exception->getMessage());
    });
}
$whoops->register();

/*
 * Setup Container
 */
$container = new Container();
$container
    ->delegate(
        // Auto-wiring based on constructor typehints.
        // http://container.thephpleague.com/auto-wiring
        new ReflectionContainer()
    );

/*
 * Initialise router
 */
//$responseFactory = new ResponseFactory();
//$strategy = new JsonStrategy($responseFactory);
//$strategy = new ApplicationStrategy();
$router = new Router();
//$strategy->setContainer($container);
//$router->setStrategy($strategy);

/*
 * Configure routes
 */
$router->map('GET', '/', [ProductController::class, 'index']);
$router->map('GET', '/data', [ProductController::class, 'data']);

/*
 * Dispatch the request to receive a response object
 */
$response = $router->dispatch($request);

/*
 * Finally send the response
 */
(new SapiEmitter())->emit($response);