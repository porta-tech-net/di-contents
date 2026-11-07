<?php

namespace App\OrderComparison\ContainerConcrete;

class OrderService
{
    public function __construct(
        private StockService $stockService,
        private PriceService $priceService,
        private OrderRepository $orderRepository
    ) {
    }

    public function placeOrder(int $productId, int $quantity): array
    {
        if (! $this->stockService->hasStock($productId, $quantity)) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }

        $total = $this->priceService->calculate($productId, $quantity);

        return ['success' => true, 'order' => $this->orderRepository->save($productId, $quantity, $total)];
    }
}
