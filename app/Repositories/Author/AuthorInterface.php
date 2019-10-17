<?php


namespace App\Repositories\Author;


interface AuthorInterface
{
    public function create($name);
    public function getAll();
}
