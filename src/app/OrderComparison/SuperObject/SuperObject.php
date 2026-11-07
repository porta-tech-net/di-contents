<?php

namespace App\OrderComparison\SuperObject;

/** Comparison-only shared service holder, modeled after CI2's superobject access. */
class SuperObject
{
    public readonly StockService $stockService;
    public readonly DiscountPriceService $priceService;
    public readonly OrderRepository $orderRepository;

    public function __construct()
    {
        $this->stockService = new StockService();
        $this->priceService = new DiscountPriceService();
        $this->orderRepository = new OrderRepository();
    }
}
