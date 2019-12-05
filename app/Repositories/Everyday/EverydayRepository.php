<?php


namespace App\Repositories\Everyday;


use App\Models\Everyday;

class EverydayRepository implements EverydayRepositoryInterface
{
    public $everyday;
    private $limit = ['id','name','author', 'ya_id'];

    /**
     * EverydayRepository constructor.
     * @param Everyday $everyday
     */
    public function __construct(Everyday $everyday)
    {
        $this->everyday = $everyday;
    }

    /**
     * Returns all records whose user_id matches the $id
     *
     * @param $id
     * @return array
     */
    public function getByUser($id)
    {
        return $this->everyday->where('user_id', '=', $id)->get($this->limit)->toArray();
    }
}
