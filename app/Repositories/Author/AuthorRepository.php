<?php


namespace App\Repositories\Author;

use App\Models\Author;

class AuthorRepository implements AuthorRepositoryInterface
{
    public $author;

    /**
     * @var string
     */

    public function __construct(Author $author)
    {
        $this->author = $author;
    }

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

    private function getByName($name)
    {
        return $this->author->where('name', '=', $name)->get()->toArray();
    }

    public function getAll()
    {
        return $this->author->query()->get()->toArray();
    }
}
