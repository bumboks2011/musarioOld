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
            ->where('playlist_id', '=', $playlistId)
            ->orderBy('pos_id')
            ->get(['songs.id','songs.name','songs.author_id','songs.genre_id','songs.begin','orders.pos_id'])
            ->toArray();
    }

    public function getOrderBySongId($songId)
    {
        return $this->order->where('song_id','=',$songId)->get()->toArray();
    }

    public function deleteOrderById($id)
    {
        $order = $this->order->find($id);
        $songId = $order->song_id;
        $order->delete();
        return $songId;
    }

    public function changePosId($direction,$posId, $playlistId)
    {
        /*
         * TODO
         *  I tried to pass the second argument for sorting,
         *  but regardless of the value passed, sorting in descending order is performed if the second element is passed.
         *  "https://github.com/jarektkaczyk/eloquence/issues/94"
         *  "https://github.com/laravel/framework/issues/22899"
         *  "https://github.com/laravel/ideas/issues/1001"
        */

        if ($direction == '>') {
            $song = $this->order
                ->where('playlist_id', '=', $playlistId)
                ->where('pos_id', $direction, $posId)
                ->orderBy('pos_id')
                ->first();
        } else {
            $song = $this->order
                ->where('playlist_id', '=', $playlistId)
                ->where('pos_id', $direction, $posId)
                ->orderBy('pos_id', 'DESC')
                ->first();
        }

        if(!$song) {
            return false;
        }

        $posTmp = $song->pos_id;
        $idTmp = $song->id;
        $song->pos_id = $posId;
        $song->save();

        $song = $this->order
            ->where('playlist_id', '=', $playlistId)
            ->where('pos_id', '=', $posId)
            ->where('id', '!=', $idTmp)
            ->first();
        $song->pos_id = $posTmp;
        $song->save();

        return true;
    }
}
