<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(): Collection
    {
        return Cache::store('redis')->remember('order:all', 30, function () {
            return Order::with('items')->where('status', '!=', 'delivered')->get();
        });
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
