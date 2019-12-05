<?php


namespace App\Services\Service;


use App\Repositories\Everyday\EverydayRepository;
use App\Repositories\Genre\GenreRepository;

class ServiceService implements ServiceServiceInterface
{
    private $urlApple = 'http://itunes.apple.com/search?term=';
    private $nipleApple = '100x100bb.jpg';
    private $pathApple = '939x0w.jpg';
    private $urlYouTube = 'https://www.youtube.com/results?sp=EgIQAQ%253D%253D&search_query=';
    private $genreRepository;
    private $everydayRepository;
    private $yaBaseUrl = 'https://api.music.yandex.net';

    /**
     * ServiceService constructor.
     * @param GenreRepository $genre
     * @param EverydayRepository $everyday
     */
    public function __construct(GenreRepository $genre, EverydayRepository $everyday)
    {
        $this->genreRepository = $genre;
        $this->everydayRepository = $everyday;
    }

    /**
     * Gets the genre by song title
     *
     * @param $data
     * @return bool|int
     */
    public function getGenre($data) {
        $url = $this->urlApple . urlencode($data->name);
        $out = $this->curl($url);
        if ($out['results']) {
            $genre = $out['results'][0]['primaryGenreName'];
            return $this->genreRepository->create($genre);
        } else {
            return false;
        }
    }

    /**
     * Gets the genre by song title
     *
     * @param $data
     * @return bool|string
     */
    public function getCover($data) {
        $url = $this->urlApple . urlencode($data->name);
        $out = $this->curl($url);
        if ($out['results']) {
            $url = $out['results'][0]['artworkUrl100'];
            return substr($url, 0, stripos($url, $this->nipleApple)) . '' . $this->pathApple;
        } else {
            return false;
        }
    }

    /**
     * Searches for music by name using ya api
     *
     * @param $data
     * @return array
     */
    public function getSearchByName($data) {
        $out = $this->curl($this->yaBaseUrl . '/search?text=' . urlencode($data->name) . '&page=0&type=track');
        //return $out;
        $tracks = [];
        for ($i = 0; $i < 20; $i++) {
            $author = empty($out['result']['tracks']['results'][$i]['artists']) ? 'Unknown' : $out['result']['tracks']['results'][$i]['artists'][0]['name'];
            $tracks[] = [
                'id' => $out['result']['tracks']['results'][$i]['id'],
                'author' => $author,
                'name' => $author . ' - ' . $out['result']['tracks']['results'][$i]['title'],
                'link' => null,
            ];
        }
        return $tracks;
    }

    /**
     * Converts ya id songs to song link
     *
     * @param $data
     * @return string
     */
    public function getUrlById($data) {
        $info = $this->curl($this->yaBaseUrl . '/tracks/' . $data->id . '/download-info');
        $downloadInfoUrl = '';
        $bitrate = 0;
        foreach ($info['result'] as $item) {
            if($item['codec'] === 'mp3' && $item['bitrateInKbps'] > $bitrate) {
                $downloadInfoUrl = $item['downloadInfoUrl'];
            }
        }

        $downloadInfo = $this->curl($downloadInfoUrl, false);
        $downloadInfo = simplexml_load_string($downloadInfo);
        // Convert into json
        $downloadInfo = json_encode($downloadInfo);
        // Convert into associative array
        $downloadInfo = json_decode($downloadInfo, true);

        $sign = md5('XGRlBW9FXlekgbPrRHuSiA' . $downloadInfo['path']);

        $url = 'https://' . $downloadInfo['host'] . '/get-mp3/' . $sign . '/' . $downloadInfo['ts'] . $downloadInfo['path'];

        $output = $url;
        return $output;
    }

    /**
     * Gets user id daily playlist
     *
     * @param $data
     * @return array
     */
    public function getEveryday($data) {
        return $this->everydayRepository->getByUser($data->user()->id);
    }

    /**
     * fulfills curl request
     *
     * @param $url
     * @param bool $decode
     * @return bool|mixed|string
     */
    private function curl($url, $decode = true) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);

        return $decode ? json_decode($out, true) : $out;
    }
}
