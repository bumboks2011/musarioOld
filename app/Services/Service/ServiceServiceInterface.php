<?php


namespace App\Services\Service;


interface ServiceServiceInterface
{
    /**
     * Gets the genre by song title
     *
     * @param $data
     * @return bool|int
     */
    public function getGenre($data);

    /**
     * Gets the genre by song title
     *
     * @param $data
     * @return bool|string
     */
    public function getCover($data);

    /**
     * Searches for music by name using ya api
     *
     * @param $data
     * @return array
     */
    public function getSearchByName($data);

    /**
     * Converts ya id songs to song link
     *
     * @param $data
     * @return string
     */
    public function getUrlById($data);

    /**
     * Gets user id daily playlist
     *
     * @param $data
     * @return array
     */
    public function getEveryday($data);
}
