<?php

namespace App\Http\Controllers\Api\History;

use App\Http\Requests\Api\History\CreateHistory;
use App\Services\History\HistoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param CreateHistory $request
     * @param HistoryService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreateHistory $request, HistoryService $service)
    {
        return response()->json(['id'=> $service->create($request)], 200);
    }
}
