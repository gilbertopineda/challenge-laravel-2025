<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Collection;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function create(array $data): ?Order
    {
        return $this->orderRepository->create($data);
    }

    public function get(string $id): ?Order
    {
        $order = $this->orderRepository->find($id);

        if (!$order) return null;

        $orderTotal = $order->items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        $order->total = $orderTotal;

        return $order;
    }

    public function getAll(): Collection
    {
        return $this->orderRepository->all();
    }

    public function update(string $id, array $data): bool
    {
        $order = $this->orderRepository->find($id);

        if (!$order) return false;

        switch ($order->status) {
            case 'initiated':
                $data['status'] = 'sent';
                return $this->orderRepository->update($id, $data);
            case 'sent':
            default:
                return $this->orderRepository->delete($id);
        }
    }
}
