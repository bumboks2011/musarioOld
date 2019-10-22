<?php


namespace App\Repositories\Author;


interface AuthorRepositoryInterface
{
    public function create($name);
    public function getAll();
}
