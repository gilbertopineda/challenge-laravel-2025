<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Order;
use App\Repositories\ItemRepositoryInterface;

class ItemService
{
    private ItemRepositoryInterface $itemRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function create(string $orderId, array $data): bool
    {
        return $this->itemRepository->create($orderId, $data);
    }
}
