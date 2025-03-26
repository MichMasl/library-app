<?php

namespace App;

use App\ErrorHandlers\HttpBadRequestExceptionHandler;
use App\ErrorHandlers\HttpNotFoundExceptionHandler;
use App\ErrorHandlers\HttpUnauthorizedExceptionHandler;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\App;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Factory\AppFactory;

define('APP_ROOT', dirname(__DIR__, 2));

readonly class AppBuilder
{
    public function buildApp(): App
    {
        $this->loadDotenv();
        AppFactory::setContainer($this->getContainer());
        $app = AppFactory::create();
        $app->addBodyParsingMiddleware();
        $this->setErrorHandlers($app);
        $router = new Router($app);
        $router->register();
        return $app;
    }

    private function getContainer(): Container
    {
        $containerBuilder = new ContainerBuilder();
        return $containerBuilder
            ->addDefinitions(APP_ROOT . '/config/definitions.php')
            ->build();
    }

    private function loadDotenv(): void
    {
        $dotenv = Dotenv::createImmutable(APP_ROOT);
        $dotenv->load();
    }

    private function setErrorHandlers(App $app): void
    {
        $errorMiddleware = $app->addErrorMiddleware(false, false, false);
        $errorMiddleware->setErrorHandler(HttpBadRequestException::class, HttpBadRequestExceptionHandler::class);
        $errorMiddleware->setErrorHandler(HttpUnauthorizedException::class, HttpUnauthorizedExceptionHandler::class);
        $errorMiddleware->setErrorHandler(HttpNotFoundException::class, HttpNotFoundExceptionHandler::class);
    }
}
