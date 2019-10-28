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
     * @return void
     */
    public function __invoke(GetOrder $request, OrderService $service)
    {
        return response()->json(['order'=> $service->getOrder($request)], 200);
    }
}
