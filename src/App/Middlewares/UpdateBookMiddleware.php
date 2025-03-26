<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

readonly class UpdateBookMiddleware implements MiddlewareInterface
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
        if (!array_key_exists("bookId", $data)) {
            throw new HttpBadRequestException($request, "bookId is required");
        }

        if (!array_key_exists("bookName", $data)) {
            throw new HttpBadRequestException($request, "bookName is required");
        }

        if (!array_key_exists("text", $data)) {
            throw new HttpBadRequestException($request, "text is required");
        }
    }

    private function validateValues(array $data, ServerRequestInterface $request): void
    {
        if (empty($data['bookId'])) {
            throw new HttpBadRequestException($request, "bookId cannot be empty");
        }

        if (empty($data['bookName'])) {
            throw new HttpBadRequestException($request, "bookName cannot be empty");
        }

        if (empty($data['text'])) {
            throw new HttpBadRequestException($request, "text cannot be empty");
        }
    }
}
