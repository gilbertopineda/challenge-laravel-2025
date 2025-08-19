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

        if (!$order) return response()->json(['success' => false]);

        $items = $this->itemService->create($order->id, $request->only(['items']));

        if (!$items) return response()->json(['success' => false]);

        return new OrderResource($order->load('items'));
    }

    public function show(string $id)
    {
        $order = $this->orderService->get($id);

        if (!$order) return response()->json(['success' => false]);

        return new OrderResource($order);
    }

    public function index()
    {
        $orders = $this->orderService->getAll();

        return OrderResource::collection($orders);
    }

    public function update(Request $request, string $id)
    {
        $updated = $this->orderService->update($id, $request->all());
        return response()->json(['success' => $updated]);
    }
}
