<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Requests\Api\Order\DeleteOrder;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param DeleteOrder $request
     * @param OrderService $service
     * @return void
     */
    public function __invoke(DeleteOrder $request, OrderService $service)
    {
        return response()->json($service->deleteOrder($request), 200);
    }
}
