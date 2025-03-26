<?php

namespace App\Repositories;

readonly class ThirdPartyBookServiceRepository
{
    private const BASE_URL = 'https://www.googleapis.com';
    public function findBookByName(string $name): array
    {
        $listOfBooks = [];
        $url = self::BASE_URL . '/books/v1/volumes?q=';
        $query = urlencode($name);
        $response = json_decode(
            file_get_contents($url . $query),
            true
        );
        foreach ($response['items'] as $bookItem) {
            $book = [
                'id' => $bookItem['id'],
                'title' => $bookItem['volumeInfo']['title'],
                'description' =>  $bookItem['volumeInfo']['description']
            ];
            $listOfBooks[] = $book;
        }
        return $listOfBooks;
    }

    public function findBookById(string $uuid): array
    {
        $url = self::BASE_URL . '/books/v1/volumes/';
        $query = urlencode($uuid);
        $response = json_decode(
            file_get_contents($url . $query),
            true
        );
        return array(
            'name' => $response['volumeInfo']['title'],
            'text' => $response['selfLink']
        );
    }
}
