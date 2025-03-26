<?php

namespace App;

use App\Controllers\GetUsersController;
use App\Controllers\GrandAccessController;
use App\Controllers\LibraryController;
use App\Controllers\SaveSearchedBookController;
use App\Controllers\SearchBookController;
use App\Controllers\SignUpController;
use App\Controllers\SignInController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\BookParamsMiddleware;
use App\Middlewares\FileExtensionMiddleware;
use App\Middlewares\GrandAccessMiddleware;
use App\Middlewares\JsonResponseHeaderMiddleware;
use App\Middlewares\SaveSearchedBookMiddleware;
use App\Middlewares\SearchBookMiddleware;
use App\Middlewares\SignUpMiddleware;
use App\Middlewares\SignInMiddleware;
use App\Middlewares\UpdateBookMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

readonly class Router
{
    public function __construct(private App $app)
    {
    }

    public function register(): void
    {
        $app = $this->app;

        $app->group('', function (RouteCollectorProxy $group) {
            $group->post('/signup', SignUpController::class)->add(SignUpMiddleware::class);
            $group->post('/signin', SignInController::class)->add(SignInMiddleware::class);

            $group->group('', function (RouteCollectorProxy $group) {
                $group->get('/users', GetUsersController::class);
                $group->post('/grand-access', GrandAccessController::class)->add(GrandAccessMiddleware::class);

                $group->get('/books', LibraryController::class . ':getAllBooks');
                $group->get('/book', LibraryController::class . ':getBook')->add(BookParamsMiddleware::class);
                $group->post('/book', LibraryController::class . ':addBook')->add(FileExtensionMiddleware::class);
                $group->put('/book', LibraryController::class . ':updateBook')->add(UpdateBookMiddleware::class);
                $group->delete('/book', LibraryController::class . ':deleteBook')->add(BookParamsMiddleware::class);

                $group->get('/search-book', SearchBookController::class)->add(SearchBookMiddleware::class);
                $group->post('/save-searched-book', SaveSearchedBookController::class)->add(SaveSearchedBookMiddleware::class);
            })->add(AuthMiddleware::class);

        })->add(JsonResponseHeaderMiddleware::class);
    }

}
