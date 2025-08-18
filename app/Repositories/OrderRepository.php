<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(): Collection
    {
        // TODO: Implement all() method.
    }

    public function find(string $id): ?Order
    {
        return Order::with('items')->findOrFail($id);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(string $id, array $data): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(string $id): bool
    {
        // TODO: Implement delete() method.
    }
}
