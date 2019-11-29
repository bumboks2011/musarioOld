<?php

namespace App\Http\Controllers\Api\Service;

use App\Services\Service\ServiceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetEverydayController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param ServiceService $service
     * @return void
     */
    public function __invoke(Request $request, ServiceService $service)
    {
        return response()->json($service->getEveryday($request), 200);
    }
}
