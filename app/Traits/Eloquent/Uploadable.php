<?php


namespace App\Traits\Eloquent;

use Illuminate\Support\Facades\Storage;

trait Uploadable
{
    /**
     * writes the downloaded $file to the storage
     *
     * @param $file
     * @param $filename
     * @param string $folder
     * @param string $storage
     * @return bool
     */
    public function upload($file, $filename, $folder = 'uploads', $storage = 'public')
    {
        $path = Storage::disk($storage)->putFileAs($folder, $file, $filename);

        if (Storage::disk($storage)->exists($path)) {
            return true;
        }

        return false;
    }
}
