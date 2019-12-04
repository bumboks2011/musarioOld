<?php


namespace App\Services\Playlist;


use App\Repositories\Order\OrderRepository;
use App\Repositories\Playlist\PlaylistRepository;

class PlaylistService implements PlaylistServiceInterface
{
    private $playlistRepository;
    private $orderRepository;

    public function __construct(PlaylistRepository $playlist, OrderRepository $order)
    {
        $this->playlistRepository = $playlist;
        $this->orderRepository = $order;
    }

    public function create($data)
    {
        return $this->playlistRepository->create($data->user()->id, $data->name);
    }

    public function update($data)
    {
        return $this->playlistRepository->update($data->id, $data->name);
    }

    public function delete($data)
    {
        if (!$this->orderRepository->getOrderByPlaylist($data->id)) {
            if ($this->playlistRepository->delete($data->id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getAll($data)
    {
        return $this->playlistRepository->getAll($data->user()->id);
    }
}
