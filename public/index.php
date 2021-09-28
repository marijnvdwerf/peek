<?php

use Marijnvdwerf\Peek\ShutdownHandler;
use Marijnvdwerf\Peek\TwigErrorRenderer;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Handlers\ErrorHandler;
use Slim\ResponseEmitter;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

define('ROOT', __DIR__ . '/..');

require ROOT . '/vendor/autoload.php';

$logError = false;
$logErrorDetails = false;
$displayErrorDetails = true;


$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();


$twig = Twig::create(ROOT . '/resources/views', ['cache' => false, 'debug' => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$app->add(TwigMiddleware::create($app, $twig));

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new ErrorHandler($callableResolver, $responseFactory);
$errorHandler->registerErrorRenderer('text/html', new TwigErrorRenderer($twig));

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);