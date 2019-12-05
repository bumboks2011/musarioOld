<?php


namespace App\Repositories\Author;


interface AuthorRepositoryInterface
{
    /**
     * Checks for the presence of a $name in the table and, if found, returns an id record.
     * Otherwise, it creates an entry with the name and returns its id.
     *
     * @param $name
     * @return int
     */
    public function create($name);

    /**
     * return all authors
     *
     * @return array
     */
    public function getAll();
}
