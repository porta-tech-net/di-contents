<?php

namespace App\OrderComparison\SuperObject;

/** Comparison-only shared service holder, modeled after CI2's superobject access. */
class SuperObject
{
    public readonly StockService $stockService;
    public readonly PriceService $priceService;
    public readonly OrderRepository $orderRepository;

    public function __construct()
    {
        $this->stockService = new StockService();
        $this->priceService = new PriceService();
        $this->orderRepository = new OrderRepository();
    }
}
