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
        $result = $this->getByName($name);
        if (empty($result)) {
            $this->genre->name = trim($name);
            $this->genre->save();
            return $this->genre->id;
        }

        return $result[0]['id'];
    }

    private function getByName($name)
    {
        return $this->genre->where('name', '=', $name)->get()->toArray();
    }

    public function getAll()
    {
        return $this->genre->query()->get()->toArray();
    }
}
