<?php

namespace App\OrderComparison\Trait;

class OrderService
{
    use OrderProcessingTrait;

    public function __construct(
        private StockService $stockService,
        private PriceService $priceService,
        private OrderRepository $orderRepository
    ) {
    }
}
