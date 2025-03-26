<?php

namespace App\Controllers;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

readonly class GrandAccessController
{
    public function __construct(private UserService $userService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $apiKey = $request->getHeaderLine('X-API-KEY');
        $result = $this->userService->grandAccess($apiKey, $data['userId']);
        if ($result) {
            $response->getBody()->write(json_encode([
                'status' => 'ok',
                'message' => 'access granted'
            ]));
            return $response;
        } else {
            throw new HttpBadRequestException($request, 'access was not granted');
        }
    }

}
