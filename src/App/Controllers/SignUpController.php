<?php

namespace App\Controllers;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

readonly class SignUpController
{
    public function __construct(private UserService $userService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $result = $this->userService->signUp(
            login: $data['login'],
            password: $data['password']
        );

        if ($result['status'] === 'error') {
            throw new HttpBadRequestException($request, $result['message']);
        }

        $response->getBody()->write(json_encode($result));
        return $response;
    }
}
