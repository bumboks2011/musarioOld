<?php

namespace App\Http\Controllers\Api\Author;

use App\Http\Requests\Api\Author\CreateAuthor;
use App\Http\Controllers\Controller;
use App\Services\Author\AuthorService;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param CreateAuthor $request
     * @param AuthorService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreateAuthor $request, AuthorService $service)
    {
        return response()->json(['id'=> $service->create($request)], 200);
    }
}
