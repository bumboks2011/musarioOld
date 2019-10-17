<?php


namespace App\Repositories\Song;

use App\Models\Song;

class SongRepository implements SongInterface
{
    public $song;
    private $limit = ['id','name','author_id', 'genre_id', 'begin'];
    /**
     * @var string
     */

    public function __construct(Song $song)
    {
        $this->song = $song;
    }

    public function create($user_id, $name)
    {
        $this->song->user_id = $user_id;
        $this->song->name = $name;
        $this->song->save();

        return true;
    }

    public function getAll($user_id)
    {
        return $this->song->query()->where('user_id', '=', $user_id)->get($this->limit)->toArray();
    }
}
