<?php


namespace App\Repositories\Song;


interface SongRepositoryInterface
{
    /**
     * Creates an entry in the song table and
     * adds an entry to the order table so that the song is the last in the list
     *
     * @param $user_id
     * @param $name
     * @param $playlist_id
     * @param int $authorId
     * @param int $genreId
     * @return int
     */
    public function create($user_id, $name, $playlist_id, $authorId = 1, $genreId = 1);

    /**
     * Returns records where column user_id equals to $user_id
     *
     * @param $user_id
     * @return array
     */
    public function getAll($user_id);

    /**
     * Updates a record by id
     *
     * @param $id
     * @param $name
     * @param $author_id
     * @param $genre_id
     * @return bool
     */
    public function update($id, $name, $author_id, $genre_id);

    /**
     * Deletes a record by id
     *
     * @param $songId
     * @return bool
     */
    public function delete($songId);
}
