<?php


namespace App\Traits\Eloquent;


use Illuminate\Support\Facades\Storage;

trait Urlable
{
    public function downloadByUrl($url, $filename, $folder = 'uploads', $storage = 'public')
    {
        //$path = Storage::disk($storage)->copy($url, '/' . $folder . '/' . $filename . '.mp3');
        $contents = file_get_contents($url);
        $path = Storage::disk($storage)->put('/' . $folder . '/' . $filename . '.mp3', $contents);
        return $path;
    }

    public function endDownloadByUrl($id, $name, $folder = 'uploads', $storage = 'public') {
        $path = Storage::disk($storage)->move($folder . '/' . $name . '.mp3', '/' . $folder . '/' . $id . '.mp3');
        return $path;
    }
}
