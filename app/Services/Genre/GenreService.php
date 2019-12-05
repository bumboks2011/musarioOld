<?php


namespace App\Services\Genre;


use App\Repositories\Genre\GenreRepository;

class GenreService implements GenreServiceInterface
{
    private $genreRepository;

    /**
     * GenreService constructor.
     * @param GenreRepository $genre
     */
    public function __construct(GenreRepository $genre)
    {
        $this->genreRepository = $genre;
    }

    /**
     * Calls the create method in the repository
     *
     * @param $data
     * @return int
     */
    public function create($data)
    {
        return $this->genreRepository->create($data->name);
    }

    /**
     * Calls the get method in the repository
     *
     * @return array
     */
    public function getAll()
    {
        return $this->genreRepository->getAll();
    }
}
