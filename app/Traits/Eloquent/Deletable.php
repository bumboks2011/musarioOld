<?php


namespace App\Traits\Eloquent;


use Illuminate\Support\Facades\Storage;

trait Deletable
{
    /**
     * deletes a file from the repository by $filename
     *
     * @param $filename
     * @param string $folder
     * @param string $storage
     * @return bool
     */
    public function delete($filename, $folder = 'uploads', $storage = 'public')
    {
        $path = Storage::disk($storage)->delete($folder . '/' . $filename);

        if (Storage::disk($storage)->exists($path)) {
            return false;
        }

        return true;
    }
}
