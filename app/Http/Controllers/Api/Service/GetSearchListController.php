<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Requests\Api\Service\GetSearchList;
use App\Services\Service\ServiceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetSearchListController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param GetSearchList $request
     * @param ServiceService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GetSearchList $request, ServiceService $service)
    {
        return response()->json($service->getSearchByName($request), 200);
    }
}
