<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

readonly class BookParamsMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $param = $request->getQueryParams();
        $this->validateRequiredParams($param, $request);
        $this->validateValues($param, $request);
        return $handler->handle($request);
    }

    private function validateRequiredParams(array $param, ServerRequestInterface $request): void
    {
        if (!array_key_exists("id", $param)) {
            throw new HttpBadRequestException($request, "id parameter is required");
        }
    }

    private function validateValues(array $param, ServerRequestInterface $request): void
    {
        if (empty($param['id'])) {
            throw new HttpBadRequestException($request, "id cannot be empty");
        }

    }
}
