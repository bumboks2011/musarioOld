<?php

namespace App\Http\Controllers\Api\Genre;

use App\Services\Genre\GenreService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param GenreService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, GenreService $service)
    {
        return response()->json($service->getAll(), 200);
    }
}
