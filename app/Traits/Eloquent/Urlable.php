<?php


namespace App\Traits\Eloquent;


use Illuminate\Support\Facades\Storage;

trait Urlable
{
    /**
     * downloads an url file and puts it in storage
     *
     * @param $url
     * @param $filename
     * @param string $folder
     * @param string $storage
     * @return bool
     */
    public function downloadByUrl($url, $filename, $folder = 'uploads', $storage = 'public')
    {
        $contents = file_get_contents($url);
        $path = Storage::disk($storage)->put('/' . $folder . '/' . $filename . '.mp3', $contents);
        return $path;
    }

    /**
     * renames from name to id
     *
     * @param $id
     * @param $name
     * @param string $folder
     * @param string $storage
     * @return bool
     */
    public function endDownloadByUrl($id, $name, $folder = 'uploads', $storage = 'public') {
        $path = Storage::disk($storage)->move($folder . '/' . $name . '.mp3', '/' . $folder . '/' . $id . '.mp3');
        return $path;
    }
}
