<?php


namespace App\Repositories\Song;

use App\Models\Song;

class SongRepository implements SongRepositoryInterface
{
    private $song;
    private $limit = ['id','name','author_id', 'genre_id', 'begin'];

    /**
     *
     * @param Song $song
     * @param FileService $file
     */

    public function __construct(Song $song)
    {
        $this->song = $song;
    }

    public function create($user_id, $name)
    {
        return $this->song->query()->create(['user_id' => $user_id, 'name' => $name])->id;
    }

    public function getAll($user_id)
    {
        return $this->song->query()->where('user_id', '=', $user_id)->get($this->limit)->toArray();
    }
}
