<?php


namespace App\Services\Playlist;


use App\Repositories\Playlist\PlaylistRepository;

class PlaylistService implements PlaylistServiceInterface
{
    private $playlistRepository;

    public function __construct(PlaylistRepository $playlist)
    {
        $this->playlistRepository = $playlist;
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
        if ($this->playlistRepository->delete($data->id)) {
            return $this->getAll($data);
        } else {
            return false;
        }
    }

    public function getAll($data)
    {
        return $this->playlistRepository->getAll($data->user()->id);
    }
}
