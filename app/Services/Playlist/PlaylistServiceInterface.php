<?php


namespace App\Services\Playlist;


interface PlaylistServiceInterface
{
    public function create($data);
    public function update($data);
    public function delete($data);
    public function getAll($data);
}
