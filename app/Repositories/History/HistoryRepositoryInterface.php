<?php


namespace App\Repositories\History;


interface HistoryRepositoryInterface
{
    public function create($userId,$name,$author,$inPlaylist);
}
