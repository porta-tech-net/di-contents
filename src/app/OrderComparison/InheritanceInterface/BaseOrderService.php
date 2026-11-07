<?php

namespace App\OrderComparison\InheritanceInterface;

abstract class BaseOrderService implements OrderServiceInterface
{
    public function __construct(
        protected StockService $stockService,
        protected OrderRepository $orderRepository
    ) {
    }

    final public function placeOrder(int $productId, int $quantity): array
    {
        if (! $this->stockService->hasStock($productId, $quantity)) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }

        $amounts = $this->calculateTotal($productId, $quantity);

        return [
            'success' => true,
            'order' => $this->orderRepository->save(
                $productId,
                $quantity,
                $amounts['subtotal'],
                $amounts['tax'],
                $amounts['total']
            ),
        ];
    }

    abstract protected function calculateTotal(
        int $productId,
        int $quantity
    ): array;
}
