<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Requests\Api\Playlist\DeletePlaylist;
use App\Services\Playlist\PlaylistService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param DeletePlaylist $request
     * @param PlaylistService $service
     * @return void
     */
    public function __invoke(DeletePlaylist $request, PlaylistService $service)
    {
        return response()->json(['status'=> $service->delete($request)], 200);
    }
}
