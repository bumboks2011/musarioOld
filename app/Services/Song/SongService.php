<?php


namespace App\Services\Song;

use App\Repositories\Song\SongRepository;

class SongService implements SongServiceInterface
{
    private $songModel;

    public function __construct(SongRepository $song)
    {
        $this->songModel = $song;
    }

    public function create($data) {

        return $this->songModel->create($data->user()->id, $data->name);
    }

    public function getAll($data) {

        return $this->songModel->getAll($data->user()->id);
    }
}
