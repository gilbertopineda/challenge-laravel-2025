<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OrderRepository implements OrderRepositoryInterface
{
    private Repository $cache;
    private const CACHE_KEY = 'order:all';
    private const CACHE_TTL = 30;

    public function __construct()
    {
        $this->cache = Cache::store(env('CACHE_DRIVER'));
    }

    public function all(): Collection
    {
        if ($this->cache->has(self::CACHE_KEY)) {
            return $this->cache->get(self::CACHE_KEY);
        }

        $orders = Order::with('items')
            ->where('status', '!=', 'delivered')
            ->get();

        $this->cache->put(self::CACHE_KEY, $orders, Carbon::now()->addSeconds(self::CACHE_TTL));

        return $orders;
    }

    public function find(string $id): ?Order
    {
        try {
            return Order::with('items')->findOrFail($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function create(array $data): ?Order
    {
        try {
            return Order::create($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(string $id, array $data): bool
    {
        return Order::where('id', $id)->update($data);
    }

    public function delete(string $id): bool
    {
        $database = Order::where('id', $id)->delete() > 0;

        $cachedOrders = $this->cache->get(self::CACHE_KEY);

        if (!$cachedOrders) return $database;

        $cachedOrders = $cachedOrders->filter(fn($order) => $order->id != $id);

        $cache = $this->cache->put(self::CACHE_KEY, $cachedOrders, Carbon::now()->addSeconds(self::CACHE_TTL));

        return $database && $cache;
    }
}
