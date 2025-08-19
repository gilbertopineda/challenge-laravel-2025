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

    public function create(array $data): Order
    {
        return $this->orderRepository->create($data);
    }

    public function get(string $id): Order
    {
        return $this->orderRepository->find($id);
    }

    public function getAll(): Collection
    {
        return $this->orderRepository->all();
    }
}
