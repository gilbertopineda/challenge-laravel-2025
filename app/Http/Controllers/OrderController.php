<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Services\ItemService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;
    private ItemService $itemService;

    public function __construct(OrderService $orderService, ItemService $itemService)
    {
        $this->orderService = $orderService;
        $this->itemService = $itemService;
    }

    public function store(Request $request)
    {
        $order = $this->orderService->create($request->only(['client_name']));

        $this->itemService->create($order, $request->only(['items']));

        return new OrderResource($order->load('items'));
    }

    public function show(string $id)
    {
        $order = $this->orderService->get($id);

        return new OrderResource($order);
    }
}
