<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\UsersBooksAccessRepository;

readonly class UserService
{
    public function __construct(
        private UserRepository             $userRepository,
        private UsersBooksAccessRepository $usersBooksAccessRepository
    ) {
    }

    public function signUp(string $login, string $password): array
    {
        $api_key = bin2hex(random_bytes(16));
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user = ['login' => $login, 'password_hash' => $password_hash, 'api_key' => $api_key];
        $result = $this->userRepository->addUser($user);
        if (!$result) {
            return array(
                "status" => "error",
                "message" => "the login is already taken"
            );
        } else {
            $this->usersBooksAccessRepository->grandAccess($result, $result);
            return array(
                "status" => "ok",
                "api_key" => $api_key
            );
        }
    }

    public function signIn(string $login, string $password): array
    {
        if (!$this->userRepository->getUser($login)) {
            return array(
                "status" => "error",
                "message" => "login is not found"
            );
        }

        $passwordHash = $this->userRepository->getUserPasswordHash($login)['password_hash'];

        if (!password_verify($password, $passwordHash)) {
            return array(
                "status" => "error",
                "message" => "invalid password"
            );
        }

        $api_key = $this->userRepository->getUserApiKeyByLogin($login)['api_key'];

        return array(
            "status" => "ok",
            "api_key" => $api_key
        );
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function grandAccess(string $apiKey, int $viewerId): bool
    {
        $userId = $this->userRepository->getUserIdByApiKey($apiKey)['user_id'];
        return $this->usersBooksAccessRepository->grandAccess($userId, $viewerId);
    }
}
