<?php


namespace App\Repositories\History;

use App\Models\History;

class HistoryRepository implements HistoryRepositoryInterface
{
    public $history;

    public function __construct(History $history)
    {
        $this->history = $history;
    }

    public function create($userId,$name,$author,$inPlaylist)
    {
        $this->history->user_id = $userId;
        $this->history->name = trim($name);
        $this->history->author = trim($author);
        $this->history->in_playlist = $inPlaylist;
        $this->history->save();

        return $this->history->id;
    }
}
