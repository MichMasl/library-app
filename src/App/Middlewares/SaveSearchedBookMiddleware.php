<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

readonly class SaveSearchedBookMiddleware implements MiddlewareInterface
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
        if (!array_key_exists("uuid", $data)) {
            throw new HttpBadRequestException($request, "uuid is required");
        }
    }

    private function validateValues(array $data, ServerRequestInterface $request): void
    {
        if (empty($data['uuid'])) {
            throw new HttpBadRequestException($request, "uuid cannot be empty");
        }
    }
}
