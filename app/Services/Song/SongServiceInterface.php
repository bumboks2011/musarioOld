<?php


namespace App\Services\Song;


interface SongServiceInterface
{
    public function create($data);
    public function getAll($data);
}
