<?php


namespace App\Repositories\Song;


interface SongRepositoryInterface
{
    public function create($user_id, $name, $playlist_id, $authorId = 1, $genreId = 1);
    public function getAll($user_id);
    public function update($id, $name, $author_id, $genre_id);
    public function delete($songId);
}
