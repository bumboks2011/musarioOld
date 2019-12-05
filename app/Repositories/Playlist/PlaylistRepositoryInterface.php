<?php


namespace App\Repositories\Playlist;


interface PlaylistRepositoryInterface
{
    /**
     * Creates an entry in a playlist table
     *
     * @param $user_id
     * @param $name
     * @return array
     */
    public function create($user_id, $name);

    /**
     * Returns an array of records in which the user_id column is equal to $user_id
     *
     * @param $user_id
     * @return array
     */
    public function getAll($user_id);

    /**
     * Deletes record by id
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * Updates record by id
     *
     * @param $id
     * @param $name
     * @return bool
     */
    public function update($id, $name);
}
