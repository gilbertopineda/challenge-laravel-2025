<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItemRepository implements ItemRepositoryInterface
{
    public function all(): Collection
    {
        // TODO: Implement all() method.
    }

    public function find(string $id): ?Item
    {
        // TODO: Implement find() method.
    }

    public function create(string $orderId, array $data): bool
    {
        $items = array_map(function ($item) use ($orderId) {
            return [
                'id' => (string)Str::ulid(),
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'order_id' => $orderId,
            ];
        }, $data['items']);

        return Item::insert($items);
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
