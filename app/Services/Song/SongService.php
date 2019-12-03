<?php


namespace App\Services\Song;

use App\Repositories\Song\SongRepository;
use App\Services\File\FileService;
use Illuminate\Support\Str;

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
        switch ($data->type) {
            case 'upload':
                foreach ($data->music as $item) {
                    $id = $this->songRepository->create($data->user()->id, substr($item->getClientOriginalName(), 0, -4), $data->playlist_id, $data->author_id, $data->genre_id);
                    $uploaded = $this->fileService->upload($item, $id . '.mp3');
                    $songs[] = ['id' => $id, 'uploaded' => $uploaded];
                }
                if (empty($songs)) {
                    return false;
                }
                break;
            case 'uptube':
                $initial = $this->fileService->initiateDownload($data->url);
                if (empty($initial['filename'])) {
                    return false;
                }
                $id = $this->songRepository->create($data->user()->id, $initial['name'], $data->playlist_id, $data->author_id, $data->genre_id);
                $initial = $this->fileService->endDownload($id,$initial['filename']);
                $songs[] = ['id' => $id, 'uploaded' => $initial];
                break;
            case 'url':
                $tmpName = Str::random(10);
                $uploaded = $this->fileService->downloadByUrl($data->url, $tmpName);
                if ($uploaded) {
                    $id = $this->songRepository->create($data->user()->id, $data->name, $data->playlist_id, $data->author_id, $data->genre_id);
                    $uploaded = $this->fileService->endDownloadByUrl($id, $tmpName);
                    $songs[] = ['id' => $id, 'uploaded' => $uploaded];
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }

        return $songs;
    }

    public function update($data)
    {
        return $this->songRepository->update($data->id, $data->name, $data->author_id, $data->genre_id);
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
