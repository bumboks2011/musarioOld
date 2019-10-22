<?php


namespace App\Repositories\Playlist;


use App\Models\Playlist;

class PlaylistRepository implements PlaylistRepositoryInterface
{
    private $playlist;
    private $limit = ['id','name'];

    /**
     * PlaylistRepository constructor.
     * @param Playlist $playlist
     */
    public function __construct(Playlist $playlist)
    {
        $this->playlist = $playlist;
    }

    public function create($user_id, $name)
    {
        $this->playlist->user_id = $user_id;
        $this->playlist->name = $name;

        return $this->playlist->save();
    }

    public function update($data)
    {
        return $this->playlist->find($data->id)->update(['name' => $data->name]);
    }

    public function getAll($user_id)
    {
        return $this->playlist->query()->where('user_id', '=', $user_id)->get($this->limit)->toArray();
    }
}
