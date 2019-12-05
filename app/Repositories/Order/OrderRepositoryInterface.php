<?php


namespace App\Repositories\Order;


interface OrderRepositoryInterface
{
    /**
     * Returns the entries from the order table the column in which the $playlistId is equal
     *
     * @param $playlistId
     * @return array
     */
    public function getOrderByPlaylist($playlistId);

    /**
     * Returns the entries from the order table the column in which the $songId is equal
     *
     * @param $songId
     * @return array
     */
    public function getOrderBySongId($songId);

    /**
     * deletes the record by id. It also edits a record indicating the record to be deleted to maintain integrity
     *
     * @param $id
     * @return int
     */
    public function deleteOrderById($id);

    /**
     * Changes pos_id for the $orderId of writing to the $nextId
     *
     * @param $posId
     * @param $nextId
     * @return bool
     */
    public function changePosId($posId, $nextId);

    /**
     * creates a record. Also changes the pos_id of the previous record to maintain integrity.
     *
     * @param $playlistId
     * @param $songId
     * @return bool
     */
    public function createOrder($playlistId, $songId);
}
