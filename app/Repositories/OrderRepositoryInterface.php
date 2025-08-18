<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function all(): Collection;

    public function find(string $id): ?Order;

    public function create(array $data): Order;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;
}
