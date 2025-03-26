<?php

namespace App\Controllers;

use App\Services\LibraryService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

readonly class LibraryController
{
    public function __construct(private LibraryService $libraryService)
    {
    }

    public function getAllBooks(Request $request, Response $response): Response
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');

        if (!empty($params = $request->getQueryParams())) {
            if (!$data = $this->libraryService->getAllBooksOfSpecifiedUser($params['id'], $apiKey)) {
                throw new HttpUnauthorizedException($request, "specified user did not allow you to view his library");
            }
        } else {
            $data = $this->libraryService->getAllBooksOfAuthorizedUser($apiKey);
        }

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function getBook(Request $request, Response $response): Response
    {
        $param = $request->getQueryParams();
        $apiKey = $request->getHeaderLine('X-API-KEY');
        $data = $this->libraryService->getBook($param['id'], $apiKey);
        if (!$data) {
            throw new HttpNotFoundException($request, "book is not found in your library");
        } else {
            $response->getBody()->write(json_encode($data));
        }
        return $response;
    }

    public function addBook(Request $request, Response $response): Response
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');

        if (empty($_FILES['text'])) {
            $data = $request->getParsedBody();
        } else {
            $files = $request->getUploadedFiles();
            $text = $files['text']->getStream()->getContents();
            $bookName = $request->getParsedBody();
            $data = ['name' => $bookName['name'], 'text' => $text];
        }

        $result = $this->libraryService->addBook($data, $apiKey);

        if ($result['status'] === 'error') {
            throw new HttpBadRequestException($request, $result['message']);
        }

        $response->getBody()->write(json_encode($result));
        return $response;
    }

    public function updateBook(Request $request, Response $response): Response
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');
        $data = $request->getParsedBody();
        $result = $this->libraryService->updateBook($data, $apiKey);
        if (!$result) {
            throw new HttpNotFoundException($request, "book is not found in your library");
        } else {
            $response->getBody()->write(json_encode($result));
        }
        return $response;
    }

    public function deleteBook(Request $request, Response $response): Response
    {
        $param = $request->getQueryParams();
        $apiKey = $request->getHeaderLine('X-API-KEY');
        $data = $this->libraryService->deleteBook($param['id'], $apiKey);
        if (!$data) {
            throw new HttpNotFoundException($request, "book is not found in your library");
        } else {
            $response->getBody()->write(json_encode($data));
        }
        return $response;
    }
}
