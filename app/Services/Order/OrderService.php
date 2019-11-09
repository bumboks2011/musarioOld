<?php


namespace App\Services\Order;


use App\Repositories\Order\OrderRepository;
use App\Services\Song\SongService;

class OrderService implements OrderServiceInterface
{
    private $orderRepository;
    private $songService;

    public function __construct(OrderRepository $order, SongService $songService)
    {
        $this->orderRepository = $order;
        $this->songService = $songService;
    }

    public function getOrder($data)
    {
        return $this->orderRepository->getOrderByPlaylist($data->id);
    }

    public function deleteOrder($data)
    {
        $songId = $this->orderRepository->deleteOrderById($data->id);
        if($this->orderRepository->getOrderBySongId($songId))
        {
            return true;
        } else {
            return $this->songService->delete($songId);
        }
    }

    public function createOrder($data)
    {
        return $this->orderRepository->createOrder($data->id, $data->song);
    }

    public function changePosId($data)
    {
        return $this->orderRepository->changePosId($data->id, $data->next);
    }
}
