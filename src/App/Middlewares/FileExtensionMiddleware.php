<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

readonly class FileExtensionMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!empty($_FILES['text'])) {
            $files = $request->getUploadedFiles();
            $filename = $files['text']->getClientFilename();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if ($extension !== 'txt') {
                throw new HttpBadRequestException($request, 'the file must be in TXT');
            }
        }
        return $handler->handle($request);
    }
}
