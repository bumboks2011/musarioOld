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
        $this->playlist->save();

        return ['id' => $this->playlist->id, 'name' => $this->playlist->name];
    }

    public function update($id, $name)
    {
        return $this->playlist->find($id)->update(['name' => $name]);
    }

    public function delete($id)
    {
        return $this->playlist->find($id)->delete();
    }

    public function getAll($user_id)
    {
        return $this->playlist->query()->where('user_id', '=', $user_id)->get($this->limit)->toArray();
    }
}
