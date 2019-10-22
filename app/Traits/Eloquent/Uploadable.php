<?php


namespace App\Traits\Eloquent;

use Illuminate\Support\Facades\Storage;

trait Uploadable
{
    public function upload($file, $filename, $folder = 'uploads', $storage = 'public')
    {
        $path = Storage::disk($storage)->putFileAs($folder, $file, $filename);

        if (Storage::disk($storage)->exists($path)) {
            return true;
        }

        return false;
    }
}
