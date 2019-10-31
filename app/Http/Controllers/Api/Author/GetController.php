<?php

namespace App\Http\Controllers\Api\Author;

use App\Services\Author\AuthorService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param AuthorService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, AuthorService $service)
    {
        return response()->json($service->getAll(), 200);
    }
}
