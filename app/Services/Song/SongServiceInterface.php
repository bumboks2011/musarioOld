<?php


namespace App\Services\Song;


interface SongServiceInterface
{
    public function create($data);
    public function getAll($data);
    public function update($data);
    public function delete($songId);
}
