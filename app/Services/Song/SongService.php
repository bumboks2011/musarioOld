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
        if(empty($data->url)) {
            foreach ($data->music as $item) {
                $id = $this->songRepository->create($data->user()->id, substr($item->getClientOriginalName(), 0, -4), $data->playlist_id, $data->author_id, $data->genre_id);
                $uploaded = $this->fileService->upload($item, $id . '.mp3');
                $songs[] = ['id' => $id, 'uploaded' => $uploaded];
            }
        } else {
            $initial = $this->fileService->initiateDownload($data->url);
            if(empty($initial['filename'])) {
                return false;
            }
            $id = $this->songRepository->create($data->user()->id, $initial['name'], $data->playlist_id, $data->author_id, $data->genre_id);
            $initial = $this->fileService->endDownload($id,$initial['filename']);
            $songs[] = ['id' => $id, 'uploaded' => $initial];
        }
        return $songs;
    }

    public function update($data)
    {
        return $this->songRepository->update($data->id, $data->name, $data->author_id, $data->genre_id, $data->begin);
    }

    public function getAll($data)
    {
        return $this->songRepository->getAll($data->user()->id);
    }

    public function delete($songId)
    {
        return $this->fileService->delete($songId.'.mp3') && $this->songRepository->delete($songId);
    }
}
