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
        if($this->getByName($name)) {
            $this->author->name = trim($name);
            $this->author->save();

        }

        return $this->author->id;
    }

    private function getByName($name)
    {
        $result = $this->author->query()->where('name', '=', $name)->get()->toArray();

        return empty($result);
    }

    public function getAll()
    {
        return $this->author->query()->get()->toArray();
    }
}
