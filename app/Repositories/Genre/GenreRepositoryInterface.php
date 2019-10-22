<?php


namespace App\Repositories\Genre;


interface GenreRepositoryInterface
{
    public function create($name);
    public function getAll();
}
