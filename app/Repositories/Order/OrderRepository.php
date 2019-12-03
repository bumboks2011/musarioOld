<?php


namespace App\Repositories\Order;


use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

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

    public function getOrderBySongId($songId)
    {
        return $this->order->where('song_id','=',$songId)->get()->toArray();
    }

    public function deleteOrderById($id)
    {
        $order = $this->order->find($id);
        $this->order->where('playlist_id', '=', $order->playlist_id)->where('pos_id', '=', $id)->update(['pos_id' => $order->pos_id]);
        $songId = $order->song_id;
        $order->delete();
        return $songId;
    }

    public function changePosId($orderId, $nextId)
    {
        $order = $this->order->find($orderId);
        $this->order->where('playlist_id', '=', $order->playlist_id)->where('pos_id', '=', $orderId)->update(['pos_id' => $order->pos_id]);
        $this->order->where('playlist_id', '=', $order->playlist_id)->where('pos_id', '=', $nextId)->update(['pos_id' => $orderId]);
        $order->pos_id = $nextId;
        $order->save();

        return true;
    }

    public function createOrder($playlistId, $songId)
    {
        $lastOrderId = $this->order->where('playlist_id','=', $playlistId)->where('pos_id', '=', 0)->value('id');
        $orderId = $this->order->query()->create(['song_id' => $songId, 'playlist_id' => $playlistId, 'pos_id' => 0])->id;
        $this->order->where('id', '=', $lastOrderId)->update(['pos_id' => $orderId]);

        return true;
    }
}
