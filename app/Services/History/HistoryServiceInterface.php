<?php


namespace App\Services\History;


interface HistoryServiceInterface
{
    /**
     * Calls the create method in the repository
     *
     * @param $data
     * @return int
     */
    public function create($data);
}
