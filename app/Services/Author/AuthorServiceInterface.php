<?php


namespace App\Services\Author;


interface AuthorServiceInterface
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
