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
     * @return void
     */
    public function __invoke(Request $request, GenreService $service)
    {
        return response()->json(['genres'=> $service->getAll()], 200);
    }
}
