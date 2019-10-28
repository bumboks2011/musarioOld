<?php


namespace App\Repositories\Song;


interface SongRepositoryInterface
{
    public function create($user_id, $name, $playlist_id, $authorId = null, $genreId = null);
    public function getAll($user_id);
    public function update($id, $name);
    public function delete($songId);
}
