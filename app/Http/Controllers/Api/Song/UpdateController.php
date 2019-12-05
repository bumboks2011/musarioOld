<?php

namespace App\Http\Controllers\Api\Song;

use App\Http\Requests\Api\Song\UpdateSong;
use App\Services\Song\SongService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateSong $request
     * @param SongService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateSong $request, SongService $service)
    {
        return response()->json($service->update($request), 200);
    }
}
