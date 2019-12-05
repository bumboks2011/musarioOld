<?php


namespace App\Repositories\Everyday;


interface EverydayRepositoryInterface
{
    /**
     * Returns all records whose user_id matches the $id
     *
     * @param $id
     * @return array
     */
    public function getByUser($id);
}
