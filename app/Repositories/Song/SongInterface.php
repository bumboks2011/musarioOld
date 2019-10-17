<?php


namespace App\Repositories\Song;


interface SongInterface
{
    public function create($user_id, $name);
}
