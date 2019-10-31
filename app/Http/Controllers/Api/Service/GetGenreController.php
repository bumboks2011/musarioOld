<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Requests\Api\Service\GetGenre;
use App\Services\Service\ServiceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetGenreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param GetGenre $request
     * @param ServiceService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GetGenre $request, ServiceService $service)
    {
        return response()->json($service->getGenre($request), 200);
    }
}
