<?php


namespace App\Repositories\Song;


interface SongRepositoryInterface
{
    public function create($user_id, $name);
    public function getAll($user_id);
}
