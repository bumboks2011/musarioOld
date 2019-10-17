<?php

namespace App\Http\Controllers\Api\Song;

use App\Http\Requests\Api\Song\CreateSong;
use App\Services\Song\SongService;
use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param CreateSong $request
     * @param SongService $service
     * @return bool
     */
    public function __invoke(CreateSong $request, SongService $service)
    {
        return response()->json(['status'=> $service->create($request)], 200);
    }
}
