<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Requests\Api\Order\GetOrder;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param GetOrder $request
     * @param OrderService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GetOrder $request, OrderService $service)
    {
        return response()->json($service->getOrder($request), 200);
    }
}
