<?php

namespace App\Http\Controllers\Api\Song;

use App\Services\Song\SongService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param SongService $service
     * @return void
     */
    public function __invoke(Request $request, SongService $service)
    {
        return response()->json($service->getAll($request), 200);
    }
}
