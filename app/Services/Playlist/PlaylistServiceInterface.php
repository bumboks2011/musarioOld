<?php


namespace App\Services\Playlist;


interface PlaylistServiceInterface
{
    /**
     * Calls the create method in the repository
     *
     * @param $data
     * @return array
     */
    public function create($data);

    /**
     * Calls the update method in the repository
     *
     * @param $data
     * @return bool
     */
    public function update($data);

    /**
     * If the playlist is empty then deletes the playlist by id
     *
     * @param $data
     * @return bool
     */
    public function delete($data);

    /**
     * Calls the get method in the repository
     *
     * @param $data
     * @return array
     */
    public function getAll($data);
}
