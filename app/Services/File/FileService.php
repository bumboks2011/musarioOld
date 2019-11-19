<?php


namespace App\Services\File;

use App\Traits\Eloquent\Deletable;
use App\Traits\Eloquent\Uploadable;
use App\Traits\Eloquent\Urlable;
use App\Traits\Eloquent\Youtubeable;

class FileService implements FileServiceInterface
{
    use Uploadable, Deletable, Youtubeable, Urlable;
}
