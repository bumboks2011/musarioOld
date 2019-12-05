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

    /**
     * Creates an entry in a playlist table
     *
     * @param $user_id
     * @param $name
     * @return array
     */
    public function create($user_id, $name)
    {
        $this->playlist->user_id = $user_id;
        $this->playlist->name = $name;
        $this->playlist->save();

        return ['id' => $this->playlist->id, 'name' => $this->playlist->name];
    }

    /**
     * Updates record by id
     *
     * @param $id
     * @param $name
     * @return bool
     */
    public function update($id, $name)
    {
        return $this->playlist->find($id)->update(['name' => $name]);
    }

    /**
     * Deletes record by id
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->playlist->find($id)->delete();
    }

    /**
     * Returns an array of records in which the user_id column is equal to $user_id
     *
     * @param $user_id
     * @return array
     */
    public function getAll($user_id)
    {
        return $this->playlist->query()->where('user_id', '=', $user_id)->get($this->limit)->toArray();
    }
}
