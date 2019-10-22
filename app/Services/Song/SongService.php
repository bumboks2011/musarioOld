<?php


namespace App\Services\Song;

use App\Repositories\Song\SongRepository;
use App\Services\File\FileService;

class SongService implements SongServiceInterface
{
    private $songRepository;
    private $fileService;

    public function __construct(SongRepository $song, FileService $fileService)
    {
        $this->songRepository = $song;
        $this->fileService = $fileService;
    }

    public function create($data)
    {
        $songs = [];
        foreach ($data->music as $item) {
            $id = $this->songRepository->create($data->user()->id, substr($item->getClientOriginalName(), 0, -4));
            $uploaded = $this->fileService->upload($item, $id . '.mp3');
            $songs[] = ['id' => $id , 'uploaded' => $uploaded];
        }
        return $songs;
    }

    public function getAll($data) {
        return $this->songRepository->getAll($data->user()->id);
    }
}
