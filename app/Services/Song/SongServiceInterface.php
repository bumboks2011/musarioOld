<?php


namespace App\Services\Song;


interface SongServiceInterface
{
    /**
     * Calls creation methods based on $data->type
     * @param $data
     * @return array|bool
     */
    public function create($data);

    /**
     * Calls the get method in the repository
     *
     * @param $data
     * @return array
     */
    public function getAll($data);

    /**
     * Calls the update method in the repository
     *
     * @param $data
     * @return bool
     */
    public function update($data);

    /**
     * Calls the delete method in the repository
     *
     * @param $songId
     * @return bool
     */
    public function delete($songId);
}
