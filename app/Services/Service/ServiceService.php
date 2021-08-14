<?php


namespace App\Services\Service;


use App\Repositories\Everyday\EverydayRepository;
use App\Repositories\Genre\GenreRepository;
use Symfony\Component\Process\Process;

class ServiceService implements ServiceServiceInterface
{
    private $urlApple = 'http://itunes.apple.com/search?term=';
    private $nipleApple = '100x100bb.jpg';
    private $pathApple = '939x0w.jpg';
    private $urlYouTube = 'https://www.youtube.com/results?sp=EgIQAQ%253D%253D&search_query=';
    private $genreRepository;
    private $everydayRepository;
    private $yaBaseUrl = 'https://api.music.yandex.net';
    private $yaAuthUrl = 'https://oauth.yandex.ru';
    private $clientId = '23cabbbdc6cd418abb4b39c32c41195d';
    private $clientSecret = '53bc75238f0c4d08a118e51fe9203300';
    private $quantitySong = 80;

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
            $key = array_search('song', array_column($out['results'], 'kind'));
            $url = $out['results'][(int) $key]['artworkUrl100'];
            return substr($url, 0, stripos($url, $this->nipleApple)) . '' . $this->pathApple;
        } else {
            return false;
        }
    }

    /**
     * Searches for music by song/author name using ya api
     *
     * @param $data
     * @return array
     */
    public function getSearchByName($data)
    {
        $type = empty($data->type) || $data->type == 'track' ? 'track' : 'artist';
        $out = $this->curl($this->yaBaseUrl . '/search?text=' . urlencode($data->name) . '&page=0&type=' . $type);
        $tracks = [];
        if ($type == 'track') {
            if (empty($out['result']['tracks']['results'])) {
                return $tracks;
            }
            $length = count($out['result']['tracks']['results']) < $this->quantitySong ? count($out['result']['tracks']['results']) : $this->quantitySong;
            for ($i = 0; $i < $length; $i++) {
                $author = empty($out['result']['tracks']['results'][$i]['artists']) ? 'Unknown' : $out['result']['tracks']['results'][$i]['artists'][0]['name'];
                $tracks[] = [
                    'id' => $out['result']['tracks']['results'][$i]['id'],
                    'author' => $author,
                    'name' => $author . ' - ' . $out['result']['tracks']['results'][$i]['title'],
                    'link' => null,
                ];
            }
        } else {
            if (empty($out['result']['artists'])) {
                return $tracks;
            }
            $out = $this->curl('https://music.yandex.ru/handlers/artist.jsx?artist=' .
                $out['result']['artists']['results'][0]['id'] .
                '&what=tracks&sort=&dir=&period=&lang=ru&overembed=false');
            $length = count($out['tracks']) < $this->quantitySong ? count($out['tracks']) : $this->quantitySong;
            $author = $out['artist']['name'];
            for ($i = 0; $i < $length; $i++) {
                $tracks[] = [
                    'id' => $out['tracks'][$i]['id'],
                    'author' => $author,
                    'name' => $author . ' - ' . $out['tracks'][$i]['title'],
                    'link' => null,
                ];
            }
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
     * Auth in ya music and get token
     *
     * @param $username
     * @param $password
     * @param string $grant_type
     * @param null $x_captcha_answer
     * @param null $x_captcha_key
     * @param null $timeout
     * @return void
     */
    public function getYaToken($grant_type = 'password') {
        if (env('YA_TOKEN_EXPIRE') > time()) {
            return;
        }
        $url =  $this->yaAuthUrl . '/token';
        $data = [
            'grant_type' => $grant_type,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => env('YA_NAME'),
            'password' => env('YA_PASS')
        ];
        $result = $this->curl($url, true, $data);
        $process = new Process(['php artisan env:set', 'YA_TOKEN', $result['access_token']]);
        $process->run();
        $process = new Process(['php artisan env:set', 'YA_TOKEN_EXPIRE', time() + $result['expires_in']]);
        $process->run();
        sleep(0.1);
    }

    /**
     * fulfills curl request
     *
     * @param $url
     * @param bool $decode
     * @param array $data
     * @return bool|mixed|string
     */
    public function curl($url, $decode = true, $data = []) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $headers = [
            'X-Yandex-Music-Client: WindowsPhone/3.17',
            'User-Agent: Windows 10',
            'Connection: Keep-Alive',
            'Authorization: OAuth ' . env('YA_TOKEN')
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data ? curl_setopt($curl, CURLOPT_POST, true) : '';
        $data ? curl_setopt($curl, CURLOPT_POSTFIELDS, $data) : '';
        $out = curl_exec($curl);
        curl_close($curl);

        return $decode ? json_decode($out, true) : $out;
    }
}
