<?php


namespace App\Services\Author;

use App\Repositories\Author\AuthorRepository;

class AuthorService implements AuthorServiceInterface
{
    private $authorRepository;

    /**
     * AuthorService constructor.
     * @param AuthorRepository $author
     */
    public function __construct(AuthorRepository $author)
    {
        $this->authorRepository = $author;
    }

    /**
     * Calls the create method in the repository
     *
     * @param $data
     * @return int
     */
    public function create($data)
    {
        return $this->authorRepository->create($data->name);
    }

    /**
     * Calls the get method in the repository
     *
     * @return array
     */
    public function getAll()
    {
        return $this->authorRepository->getAll();
    }
}
