<?php


namespace App\Services\Order;


interface OrderServiceInterface
{
    public function getOrder($data);
    public function deleteOrder($data);
    public function changePosId($data);
}
