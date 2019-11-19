<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Requests\Api\Service\GetIdOrder;
use App\Services\Service\ServiceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetIdController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param GetIdOrder $request
     * @param ServiceService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GetIdOrder $request, ServiceService $service)
    {
        return response()->json($service->getUrlById($request), 200);
    }
}
