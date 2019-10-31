<?php


namespace App\Repositories\Song;

use App\Models\Order;
use App\Models\Song;

class SongRepository implements SongRepositoryInterface
{
    private $song;
    private $order;
    private $limit = ['id','name','author_id', 'genre_id', 'begin'];

    /**
     *
     * @param Song $song
     * @param Order $order
     */

    public function __construct(Song $song, Order $order)
    {
        $this->song = $song;
        $this->order = $order;
    }

    public function create($userId, $name, $playlistId, $authorId = null, $genreId = null)
    {
        $id = $this->song->query()->create(['user_id' => $userId, 'name' => $name, 'author_id' => $authorId, 'genre_id' => $genreId])->id;
        $lastPosId = $this->order->where('playlist_id','=', $playlistId)->orderBy('pos_id','DESC')->value('pos_id');
        if ($lastPosId) {
            $lastPosId++;
        } else {
            $lastPosId = 1;
        }
        $this->order->query()->create(['song_id' => $id, 'playlist_id' => $playlistId, 'pos_id' => $lastPosId]);
        return $id;
    }

    public function getAll($user_id)
    {
        return $this->song->query()->where('user_id', '=', $user_id)->get($this->limit)->toArray();
    }

    public function update($id, $name, $author_id, $genre_id, $begin)
    {
        return $this->song->find($id)->update(['name' => $name, 'author_id' => $author_id, 'genre_id' => $genre_id, 'begin' => $begin]);
    }

    public function delete($songId)
    {
        $song = $this->song->find($songId);
        return $song->delete();
    }
}
