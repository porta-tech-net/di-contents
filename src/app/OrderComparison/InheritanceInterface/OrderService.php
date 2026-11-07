<?php

namespace App\OrderComparison\InheritanceInterface;

class OrderService extends BaseOrderService
{
    public function __construct(
        StockService $stockService,
        OrderRepository $orderRepository,
        private PriceService $priceService
    ) {
        parent::__construct($stockService, $orderRepository);
    }

    protected function calculateTotal(int $productId, int $quantity): int
    {
        return $this->priceService->calculate($productId, $quantity);
    }
}
