<?php


namespace App\Repositories\Genre;

use App\Models\Genre;

class GenreRepository implements GenreRepositoryInterface
{
    public $genre;

    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    public function create($name)
    {
        if ($this->getByName($name)) {
            $this->genre->name = trim($name);
            $this->genre->save();
        }

        return $this->genre->id;
    }

    private function getByName($name)
    {
        $result = $this->genre->query()->where('name', '=', $name)->get()->toArray();

        return empty($result);
    }

    public function getAll()
    {
        return $this->genre->query()->get()->toArray();
    }
}
