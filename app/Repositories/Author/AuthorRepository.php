<?php


namespace App\Repositories\Author;

use App\Models\Author;

class AuthorRepository implements AuthorRepositoryInterface
{
    public $author;

    /**
     * AuthorRepository constructor.
     * @param Author $author
     */
    public function __construct(Author $author)
    {
        $this->author = $author;
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
            $this->author->name = trim($name);
            $this->author->save();
            return $this->author->id;
        }

        return $result[0]['id'];
    }

    /**
     * looking for matches by author $name
     *
     * @param $name
     * @return array
     */
    private function getByName($name)
    {
        return $this->author->where('name', '=', $name)->get()->toArray();
    }

    /**
     * return all authors
     *
     * @return array
     */
    public function getAll()
    {
        return $this->author->query()->get()->toArray();
    }
}
