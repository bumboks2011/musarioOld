<?php

namespace App\Http\Controllers\Api\Genre;

use App\Http\Requests\Api\Genre\CreateGenre;
use App\Http\Controllers\Controller;
use App\Services\Genre\GenreService;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param CreateGenre $request
     * @param GenreService $service
     * @return void
     */
    public function __invoke(CreateGenre $request, GenreService $service)
    {
        return response()->json(['id'=> $service->create($request)], 200);
    }
}
