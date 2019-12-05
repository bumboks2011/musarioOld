<?php


namespace App\Services\Playlist;


use App\Repositories\Order\OrderRepository;
use App\Repositories\Playlist\PlaylistRepository;

class PlaylistService implements PlaylistServiceInterface
{
    private $playlistRepository;
    private $orderRepository;

    /**
     * PlaylistService constructor.
     * @param PlaylistRepository $playlist
     * @param OrderRepository $order
     */
    public function __construct(PlaylistRepository $playlist, OrderRepository $order)
    {
        $this->playlistRepository = $playlist;
        $this->orderRepository = $order;
    }

    /**
     * Calls the create method in the repository
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        return $this->playlistRepository->create($data->user()->id, $data->name);
    }

    /**
     * Calls the update method in the repository
     *
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        return $this->playlistRepository->update($data->id, $data->name);
    }

    /**
     * If the playlist is empty then deletes the playlist by id
     *
     * @param $data
     * @return bool
     */
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

    /**
     * Calls the get method in the repository
     *
     * @param $data
     * @return array
     */
    public function getAll($data)
    {
        return $this->playlistRepository->getAll($data->user()->id);
    }
}
