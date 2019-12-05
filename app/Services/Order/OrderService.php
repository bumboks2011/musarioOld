<?php


namespace App\Services\Order;


use App\Repositories\Order\OrderRepository;
use App\Services\Song\SongService;

class OrderService implements OrderServiceInterface
{
    private $orderRepository;
    private $songService;

    /**
     * OrderService constructor.
     * @param OrderRepository $order
     * @param SongService $songService
     */
    public function __construct(OrderRepository $order, SongService $songService)
    {
        $this->orderRepository = $order;
        $this->songService = $songService;
    }

    /**
     * Calls the get method in the repository
     *
     * @param $data
     * @return array
     */
    public function getOrder($data)
    {
        return $this->orderRepository->getOrderByPlaylist($data->id);
    }

    /**
     * Calls the delete method in the repository.
     * It also checks for the presence of a song corresponding to the order in other playlists,
     * if the song is not in other playlists, it calls the method for its removal.
     *
     * @param $data
     * @return bool
     */
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

    /**
     * Calls the create method in the repository
     *
     * @param $data
     * @return bool
     */
    public function createOrder($data)
    {
        return $this->orderRepository->createOrder($data->id, $data->song);
    }

    /**
     * Calls the change method in the repository
     *
     * @param $data
     * @return bool
     */
    public function changePosId($data)
    {
        return $this->orderRepository->changePosId($data->id, $data->next);
    }
}
