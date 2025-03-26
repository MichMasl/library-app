<?php

namespace App\Controllers;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class GetUsersController
{
    public function __construct(private UserService $userService)
    {
    }
    public function __invoke(Request $request, Response $response): Response
    {
        $data = $this->userService->getAllUsers();
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
