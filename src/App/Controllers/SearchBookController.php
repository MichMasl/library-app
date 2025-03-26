<?php

namespace App\Controllers;

use App\Services\LibraryService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

readonly class SearchBookController
{
    public function __construct(private LibraryService $libraryService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getQueryParams();
        if (empty($booksList = $this->libraryService->findBookByName($data['bookName']))) {
            throw new HttpNotFoundException($request, 'books not found by specified name');
        } else {
            $response->getBody()->write(json_encode($booksList));
        }
        return $response;
    }


}
