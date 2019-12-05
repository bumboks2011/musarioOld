<?php


namespace App\Repositories\History;


interface HistoryRepositoryInterface
{
    /**
     * creates a record of the listened song
     *
     * @param $userId
     * @param $name
     * @param $author
     * @param $inPlaylist
     * @return int
     */
    public function create($userId, $name, $author, $inPlaylist);
}
