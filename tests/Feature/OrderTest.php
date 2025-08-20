<?php

use App\Models\Order;
use App\Models\Item;

it('can create a new order with items', function () {
    $data = [
        'client_name' => 'Carlos Gómez',
        'items' => [
            ['description' => 'Lomo saltado', 'unit_price' => 1, 'quantity' => 60],
            ['description' => 'Inka Kola', 'unit_price' => 2, 'quantity' => 10],
        ],
    ];

    $this->postJson('/api/orders', $data)
        ->tap(function ($response) {
            // debug
            // $response->dump();
        })
        ->assertJson(function ($json) {
            return $json->where('data.client_name', 'Carlos Gómez')
                ->where('data.items.0.description', 'Lomo saltado')
                ->etc();
        });
});

it('can show the order details', function () {
    $order = Order::factory()->has(Item::factory()->count(2))->create();

    $this->getJson("/api/orders/{$order->id}")
        ->tap(function ($response) {
            // debug
            // $response->dump();
        })
        ->assertJson(function ($json) use ($order) {
            return $json->where('data.id', $order->id)
                ->etc();
        });
});

it('can get all existing orders', function () {
    Order::factory()->has(Item::factory()->count(2), 'items')->count(5)->create();

    $this->getJson('/api/orders')
        ->tap(function ($response) {
            // debug
            // $response->dump();
        })
        ->assertJsonCount(5, 'data');
});

it('can change the order status', function () {
    $order = Order::factory()->has(Item::factory()->count(2))->create();

    $this->postJson("/api/orders/{$order->id}/advance", [])
        ->tap(function ($response) {
            // debug
            // $response->dump();
        })
        ->assertJson(function ($json) use ($order) {
            return $json->where('success', true);
        });

    $this->getJson("/api/orders/{$order->id}")
        ->tap(function ($response) {
            // debug
            // $response->dump();
        })
        ->assertJson(function ($json) use ($order) {
            return $json->where('data.id', $order->id);
        });
});

it('can delete an order if this was delivered', function () {
    $order = Order::factory()->has(Item::factory()->count(2))->create(['status' => 'sent']);

    $this->postJson("/api/orders/{$order->id}/advance", [])
        ->tap(function ($response) {
            // debug
            // $response->dump();
        })
        ->assertJson(function ($json) use ($order) {
            return $json->where('success', true);
        });

    $this->getJson("/api/orders/{$order->id}")
        ->tap(function ($response) {
            // debug
            // $response->dump();
        })
        ->assertJson(function ($json) use ($order) {
            return $json->where('success', false);
        });
});
