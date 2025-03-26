<?php

namespace App\Services;

use App\Repositories\BooksRepository;
use App\Repositories\ThirdPartyBookServiceRepository;
use App\Repositories\UserRepository;
use App\Repositories\UsersBooksAccessRepository;
use App\Repositories\UsersBooksRepository;

readonly class LibraryService
{
    public function __construct(
        private UserRepository              $userRepository,
        private BooksRepository             $booksRepository,
        private UsersBooksRepository        $usersBooksRepository,
        private UsersBooksAccessRepository  $usersBooksAccessRepository,
        private ThirdPartyBookServiceRepository $thirdPartyBookServiceRepository
    ) {
    }

    public function getAllBooksOfAuthorizedUser(string $apiKey): array
    {
        $user = $this->userRepository->getUserIdByApiKey($apiKey);
        return $this->booksRepository->getAll($user['user_id']);
    }

    public function getAllBooksOfSpecifiedUser(string $userId, string $apiKey): array|bool
    {
        $viewer = $this->userRepository->getUserIdByApiKey($apiKey);
        $isViewerHasAccessToUsersLibrary = $this->checkUsersAccess($userId, $viewer['user_id']);
        if (!$isViewerHasAccessToUsersLibrary) {
            return false;
        }
        return $this->booksRepository->getAll($userId);
    }

    public function getBook(string $bookId, string $apiKey): array|bool
    {
        $user = $this->userRepository->getUserIdByApiKey($apiKey);
        return $this->booksRepository->getBookById($user['user_id'], $bookId);
    }

    public function addBook(array $data, string $apiKey): array
    {
        $user = $this->userRepository->getUserIdByApiKey($apiKey);
        extract($data);
        $bookId = $this->booksRepository->addBook($name, $text);
        if (!$this->usersBooksRepository->addBookToUser($user['user_id'], $bookId)) {
            return array(
                "status" => "error",
                "message" => "book was not added to users library"
            );
        } else {
            return array(
                "status" => "ok",
                "book_id" => $bookId
            );
        }
    }

    public function updateBook(array $data, string $apiKey): array|bool
    {
        $user = $this->userRepository->getUserIdByApiKey($apiKey);
        if (!$this->booksRepository->getBookById($user['user_id'], $data['bookId'])) {
            return false;
        } else {
            if (!$this->booksRepository->updateBook($data)) {
                return array(
                    "status" => "error",
                    "message" => "book was not updated"
                );
            } else {
                return array(
                    "status" => "ok",
                    "message" => "book successfully updated"
                );
            }
        }
    }

    public function deleteBook(string $bookId, string $apiKey): array|bool
    {
        $user = $this->userRepository->getUserIdByApiKey($apiKey);
        if (!$this->booksRepository->getBookById($user['user_id'], $bookId)) {
            return false;
        }
        if (!$this->booksRepository->deleteBook($user['user_id'], $bookId)) {
            return false;
        } else {
            return array(
                "status" => "ok",
                "message" => "book successfully deleted"
            );
        }
    }

    public function findBookByName(string $name): array
    {
        return $this->thirdPartyBookServiceRepository->findBookByName($name);
    }

    public function saveSearchedBook(string $uuid, string $apiKey): array
    {
        $bookFoundById = $this->thirdPartyBookServiceRepository->findBookById($uuid);
        if (empty($bookFoundById['name'])) {
            return array(
                'status' => 'error',
                'message' => 'book is not found'
            );
        }
        return $this->addBook($bookFoundById, $apiKey);
    }

    private function checkUsersAccess(string $userId, string $viewerId): bool
    {
        if (!$this->usersBooksAccessRepository->checkAccess($userId, $viewerId)) {
            return false;
        } else {
            return true;
        }
    }
}
