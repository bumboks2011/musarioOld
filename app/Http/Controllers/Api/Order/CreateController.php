<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Requests\Api\Order\CreateOrder;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param CreateOrder $request
     * @param OrderService $service
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreateOrder $request, OrderService $service)
    {
        return response()->json($service->createOrder($request), 200);
    }
}
