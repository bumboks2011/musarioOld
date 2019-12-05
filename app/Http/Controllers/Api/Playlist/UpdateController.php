<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Requests\Api\Playlist\UpdatePlaylist;
use App\Http\Controllers\Controller;
use App\Services\Playlist\PlaylistService;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param UpdatePlaylist $request
     * @param PlaylistService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdatePlaylist $request, PlaylistService $service)
    {
        return response()->json($service->update($request), 200);
    }
}
