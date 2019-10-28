<?php


namespace App\Repositories\Playlist;


interface PlaylistRepositoryInterface
{
    public function create($user_id, $name);
    public function getAll($user_id);
    public function delete($id);
    public function update($id, $name);
}
