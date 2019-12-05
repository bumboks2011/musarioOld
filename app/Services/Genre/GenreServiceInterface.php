<?php


namespace App\Services\Genre;


interface GenreServiceInterface
{
    /**
     * Calls the create method in the repository
     *
     * @param $data
     * @return int
     */
    public function create($data);

    /**
     * Calls the get method in the repository
     *
     * @return array
     */
    public function getAll();
}
