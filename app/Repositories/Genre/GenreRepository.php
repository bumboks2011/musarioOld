<?php


namespace App\Repositories\Genre;

use App\Models\Genre;

class GenreRepository implements GenreRepositoryInterface
{
    public $genre;

    /**
     * GenreRepository constructor.
     * @param Genre $genre
     */
    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    /**
     * Checks for the presence of a $name in the table and, if found, returns an id record.
     * Otherwise, it creates an entry with the $name and returns its id.
     *
     * @param $name
     * @return int
     */
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

    /**
     * looking for matches by genre $name
     *
     * @param $name
     * @return mixed
     */
    private function getByName($name)
    {
        return $this->genre->where('name', '=', $name)->get()->toArray();
    }

    /**
     * return all genres
     *
     * @return array
     */
    public function getAll()
    {
        return $this->genre->query()->get()->toArray();
    }
}
