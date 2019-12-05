<?php


namespace App\Services\Order;


interface OrderServiceInterface
{
    /**
     * Calls the get method in the repository
     *
     * @param $data
     * @return array
     */
    public function getOrder($data);

    /**
     * Calls the delete method in the repository.
     * It also checks for the presence of a song corresponding to the order in other playlists,
     * if the song is not in other playlists, it calls the method for its removal.
     *
     * @param $data
     * @return bool
     */
    public function deleteOrder($data);

    /**
     * Calls the crete method in the repository
     *
     * @param $data
     * @return bool
     */
    public function createOrder($data);

    /**
     * Calls the change method in the repository
     *
     * @param $data
     * @return bool
     */
    public function changePosId($data);
}
