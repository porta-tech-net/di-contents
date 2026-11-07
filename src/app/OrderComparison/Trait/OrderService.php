<?php

namespace App\OrderComparison\Trait;

class OrderService
{
    use OrderProcessingTrait;

    public function __construct(
        private StockService $stockService,
        private DiscountPriceService $priceService,
        private OrderRepository $orderRepository
    ) {
    }
}
