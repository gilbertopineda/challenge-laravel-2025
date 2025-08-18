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

    public function create(Order $order, array $data): void
    {
        $this->itemRepository->create($order, $data);
    }
}
