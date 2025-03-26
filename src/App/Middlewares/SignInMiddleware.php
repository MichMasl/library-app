<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

readonly class SignInMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->validateRequiredFields($data, $request);
        $this->validateValues($data, $request);
        return $handler->handle($request);
    }

    private function validateRequiredFields(array $data, ServerRequestInterface $request): void
    {
        if (!array_key_exists("login", $data)) {
            throw new HttpBadRequestException($request, "login is required");
        }

        if (!array_key_exists("password", $data)) {
            throw new HttpBadRequestException($request, "password is required");
        }
    }

    private function validateValues(array $data, ServerRequestInterface $request): void
    {
        if (empty($data['login'])) {
            throw new HttpBadRequestException($request, "login cannot be empty");
        }

        if (empty($data['password'])) {
            throw new HttpBadRequestException($request, "password cannot be empty");
        }
    }
}
