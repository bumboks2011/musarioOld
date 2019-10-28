<?php


namespace App\Services\File;

use App\Traits\Eloquent\Deletable;
use App\Traits\Eloquent\Uploadable;

class FileService implements FileServiceInterface
{
    use Uploadable, Deletable;
}
