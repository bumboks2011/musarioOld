<?php


namespace App\Traits\Eloquent;


use Illuminate\Support\Facades\Storage;

trait Deletable
{
    public function delete($filename, $folder = 'uploads', $storage = 'public')
    {
        $path = Storage::disk($storage)->delete($folder . '/' . $filename);

        if (Storage::disk($storage)->exists($path)) {
            return false;
        }

        return true;
    }
}
