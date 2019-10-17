<?php


namespace App\Services\Author;

use App\Repositories\Author\AuthorRepository;

class AuthorService implements AuthorServiceInterface
{
    private $songModel;

    public function __construct(AuthorRepository $song)
    {
        $this->songModel = $song;
    }

    public function create($data) {

        return $this->songModel->create($data->name);
    }

    public function getAll() {

        return $this->songModel->getAll();
    }
}
