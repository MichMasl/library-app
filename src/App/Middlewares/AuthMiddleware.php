<?php

namespace App\Middlewares;

use App\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

readonly class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader('X-API-KEY')) {
            throw new HttpUnauthorizedException($request, 'api-key is missing');
        }

        $api_key = $request->getHeaderLine('X-API-KEY');

        if (!$this->userRepository->findApiKey($api_key)) {
            throw new HttpUnauthorizedException($request, 'invalid api-key');
        }

        return $handler->handle($request);
    }
}
