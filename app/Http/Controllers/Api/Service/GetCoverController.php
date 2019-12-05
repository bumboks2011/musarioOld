<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Requests\Api\Service\GetCover;
use App\Services\Service\ServiceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetCoverController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param GetCover $request
     * @param ServiceService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GetCover $request, ServiceService $service)
    {
        return response()->json($service->getCover($request), 200);
    }
}
