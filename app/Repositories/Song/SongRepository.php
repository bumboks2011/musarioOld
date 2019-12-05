<?php


namespace App\Repositories\Song;

use App\Models\Order;
use App\Models\Song;

class SongRepository implements SongRepositoryInterface
{
    private $song;
    private $order;
    private $limit = ['id','name','author_id', 'genre_id'];

    /**
     * SongRepository constructor.
     * @param Song $song
     * @param Order $order
     */
    public function __construct(Song $song, Order $order)
    {
        $this->song = $song;
        $this->order = $order;
    }

    /**
     * Creates an entry in the song table and
     * adds an entry to the order table so that the song is the last in the list
     *
     * @param $userId
     * @param $name
     * @param $playlistId
     * @param int $authorId
     * @param int $genreId
     * @return int
     */
    public function create($userId, $name, $playlistId, $authorId = 1, $genreId = 1)
    {
        $id = $this->song->query()->create(['user_id' => $userId, 'name' => $name, 'author_id' => $authorId, 'genre_id' => $genreId])->id;
        $lastOrderId = $this->order->where('playlist_id','=', $playlistId)->where('pos_id', '=', 0)->value('id');
        $orderId = $this->order->query()->create(['song_id' => $id, 'playlist_id' => $playlistId, 'pos_id' => 0])->id;
        $this->order->where('id', '=', $lastOrderId)->update(['pos_id' => $orderId]);

        return $id;
    }

    /**
     * Returns records where column user_id equals to $user_id
     *
     * @param $user_id
     * @return array
     */
    public function getAll($user_id)
    {
        return $this->song->query()->where('user_id', '=', $user_id)->get($this->limit)->toArray();
    }

    /**
     * Updates a record by id
     *
     * @param $id
     * @param $name
     * @param $author_id
     * @param $genre_id
     * @return bool
     */
    public function update($id, $name, $author_id, $genre_id)
    {
        return $this->song->find($id)->update(['name' => $name, 'author_id' => $author_id, 'genre_id' => $genre_id]);
    }

    /**
     * Deletes a record by id
     *
     * @param $songId
     * @return bool
     */
    public function delete($songId)
    {
        $song = $this->song->find($songId);
        return $song->delete();
    }
}
