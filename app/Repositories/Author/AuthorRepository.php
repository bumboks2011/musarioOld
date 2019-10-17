<?php


namespace App\Repositories\Author;

use App\Models\Author;

class AuthorRepository implements AuthorInterface
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
            $this->author->name = $name;
            $this->author->save();

        }

        return $this->author->id;
    }

    public function getByName($name)
    {
        $result = $this->author->query()->where('name', '=', $name)->get()->toArray();

        return empty($result);
    }

    public function getAll()
    {
        return $this->author->query()->get()->toArray();
    }
}
