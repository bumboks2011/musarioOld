<?php


namespace App\Services\Genre;


use App\Repositories\Genre\GenreRepository;

class GenreService implements GenreServiceInterface
{
    private $genreRepository;

    /**
     * @param GenreRepository $genre
     */
    public function __construct(GenreRepository $genre)
    {
        $this->genreRepository = $genre;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->genreRepository->create($data->name);
    }

    public function getAll() {

        return $this->genreRepository->getAll();
    }
}
