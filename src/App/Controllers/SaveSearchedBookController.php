<?php

namespace App\Controllers;

use App\Services\LibraryService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

readonly class SaveSearchedBookController
{
    public function __construct(private LibraryService $libraryService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');
        $data = $request->getParsedBody();
        $result = $this->libraryService->saveSearchedBook($data['uuid'], $apiKey);
        if ($result['status'] === 'error') {
            throw new HttpNotFoundException($request, $result['message']);
        }
        $response->getBody()->write(json_encode($result));
        return $response;
    }


}
