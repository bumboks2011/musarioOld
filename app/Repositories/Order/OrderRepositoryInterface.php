<?php


namespace App\Repositories\Order;


interface OrderRepositoryInterface
{
    public function getOrderByPlaylist($playlistId);
    public function getOrderBySongId($songId);
    public function deleteOrderById($id);
    public function changePosId($posId, $nextId);
}
