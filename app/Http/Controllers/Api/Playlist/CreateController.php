<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Requests\Api\Playlist\CreatePlaylist;
use App\Http\Controllers\Controller;
use App\Services\Playlist\PlaylistService;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param CreatePlaylist $request
     * @param PlaylistService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreatePlaylist $request, PlaylistService $service)
    {
        return response()->json(['status'=> $service->create($request)], 200);
    }
}
