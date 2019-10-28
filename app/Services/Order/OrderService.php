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

    public function changePosId($data)
    {
        if ($data->action == 'up') {
            $direction = '>';
        } else {
            $direction = '<';
        }

        return $this->orderRepository->changePosId($direction, $data->id, $data->playlist_id);
    }
}
