<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Requests\Api\Order\UpdateOrder;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateOrder $request
     * @param OrderService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateOrder $request, OrderService $service)
    {
        return response()->json($service->changePosId($request), 200);
    }
}
