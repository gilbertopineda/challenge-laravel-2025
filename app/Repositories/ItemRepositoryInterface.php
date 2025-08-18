<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Collection;

interface ItemRepositoryInterface
{
    public function all(): Collection;

    public function find(string $id): ?Item;

    public function create(Order $order, array $data): void;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;
}
