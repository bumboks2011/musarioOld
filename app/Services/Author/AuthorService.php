<?php


namespace App\Services\Author;

use App\Repositories\Author\AuthorRepository;

class AuthorService implements AuthorServiceInterface
{
    private $authorRepository;

    public function __construct(AuthorRepository $author)
    {
        $this->authorRepository = $author;
    }

    public function create($data) {

        return $this->authorRepository->create($data->name);
    }

    public function getAll() {

        return $this->authorRepository->getAll();
    }
}
