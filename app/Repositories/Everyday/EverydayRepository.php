<?php


namespace App\Repositories\Everyday;


use App\Models\Everyday;

class EverydayRepository implements EverydayRepositoryInterface
{
    public $everyday;
    private $limit = ['id','name','author', 'ya_id'];

    public function __construct(Everyday $everyday)
    {
        $this->everyday = $everyday;
    }

    public function getByUser($id)
    {
        return $this->everyday->where('user_id', '=', $id)->get($this->limit)->toArray();
    }
}
