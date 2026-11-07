<?php

namespace App\OrderComparison\Inheritance;

class OrderService extends BaseOrderService
{
    public function __construct(
        StockService $stockService,
        OrderRepository $orderRepository,
        private DiscountPriceService $priceService
    ) {
        parent::__construct($stockService, $orderRepository);
    }

    protected function calculateTotal(
        int $productId,
        int $quantity
    ): array {
        return $this->priceService->calculate(
            $productId,
            $quantity
        );
    }
}
