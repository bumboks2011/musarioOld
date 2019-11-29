<?php


namespace App\Services\History;


use App\Repositories\History\HistoryRepository;

class HistoryService implements HistoryServiceInterface
{
    private $historyRepository;

    /**
     * @param HistoryRepository $history
     */
    public function __construct(HistoryRepository $history)
    {
        $this->historyRepository = $history;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->historyRepository->create($data->user()->id, $data->name, $data->author, $data->inPlaylist);
    }
}
