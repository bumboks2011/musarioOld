<?php


namespace App\Repositories\Order;


use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    private $order;

    /**
     * OrderRepository constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Returns the entries from the order table the column in which the $playlistId is equal
     *
     * @param $playlistId
     * @return array
     */
    public function getOrderByPlaylist($playlistId)
    {
        return $this->order->query()
            ->join("songs","songs.id","=","orders.song_id")
            ->join("authors","authors.id","=","songs.author_id")
            ->where('playlist_id', '=', $playlistId)
            ->orderBy('pos_id', 'desc')
            ->get(['songs.id', 'orders.id as orderId','songs.name','songs.author_id','authors.name as author','songs.genre_id','orders.pos_id'])
            ->toArray();
    }

    /**
     * Returns the entries from the order table the column in which the $songId is equal
     *
     * @param $songId
     * @return array
     */
    public function getOrderBySongId($songId)
    {
        return $this->order->where('song_id','=',$songId)->get()->toArray();
    }

    /**
     * deletes the record by id. It also edits a record indicating the record to be deleted to maintain integrity
     *
     * @param $id
     * @return int
     */
    public function deleteOrderById($id)
    {
        $order = $this->order->find($id);
        $this->order->where('playlist_id', '=', $order->playlist_id)->where('pos_id', '=', $id)->update(['pos_id' => $order->pos_id]);
        $songId = $order->song_id;
        $order->delete();
        return $songId;
    }

    /**
     * Changes pos_id for the $orderId of writing to the $nextId
     *
     * @param $orderId
     * @param $nextId
     * @return bool
     */
    public function changePosId($orderId, $nextId)
    {
        $order = $this->order->find($orderId);
        $this->order->where('playlist_id', '=', $order->playlist_id)->where('pos_id', '=', $orderId)->update(['pos_id' => $order->pos_id]);
        $this->order->where('playlist_id', '=', $order->playlist_id)->where('pos_id', '=', $nextId)->update(['pos_id' => $orderId]);
        $order->pos_id = $nextId;
        $order->save();

        return true;
    }

    /**
     * creates a record. Also changes the pos_id of the previous record to maintain integrity.
     *
     * @param $playlistId
     * @param $songId
     * @return bool
     */
    public function createOrder($playlistId, $songId)
    {
        $lastOrderId = $this->order->where('playlist_id','=', $playlistId)->where('pos_id', '=', 0)->value('id');
        $orderId = $this->order->query()->create(['song_id' => $songId, 'playlist_id' => $playlistId, 'pos_id' => 0])->id;
        $this->order->where('id', '=', $lastOrderId)->update(['pos_id' => $orderId]);

        return true;
    }
}
