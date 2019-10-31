<?php


namespace App\Services\Service;


use App\Repositories\Genre\GenreRepository;

class ServiceService implements ServiceServiceInterface
{
    private $url = 'http://itunes.apple.com/search?term=';
    private $niple = '100x100bb.jpg';
    private $path = '939x0w.jpg';
    private $genreRepository;

    /**
     * @param GenreRepository $genre
     */
    public function __construct(GenreRepository $genre)
    {
        $this->genreRepository = $genre;
    }

    public function getGenre($data) {
        $url = $this->url . urlencode($data->name);
        $out = $this->curl($url);
        $genre = $out['results'][0]['primaryGenreName'];
        return $this->genreRepository->create($genre);
    }

    public function getCover($data) {
        $url = $this->url . urlencode($data->name);
        $out = $this->curl($url);
        $url = $out['results'][0]['artworkUrl100'];
        return substr($url, 0, stripos($url, $this->niple)) . '' . $this->path;
    }

    private function curl($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);

        return json_decode($out, true);
    }
}
