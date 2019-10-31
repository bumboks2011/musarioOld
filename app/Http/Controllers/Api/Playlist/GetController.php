<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Services\Playlist\PlaylistService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param PlaylistService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, PlaylistService $service)
    {
        return response()->json($service->getAll($request), 200);
    }
}
