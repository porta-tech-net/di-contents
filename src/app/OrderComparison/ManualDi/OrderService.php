<?php

namespace App\OrderComparison\ManualDi;

class OrderService
{
    public function __construct(
        private StockService $stockService,
        private DiscountPriceService $priceService,
        private OrderRepository $orderRepository
    ) {
    }

    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array {
        if (
            $orderType === 'normal'
            && ! $this->stockService->hasStock($productId, $quantity)
        ) {
            return [
                'success' => false,
                'message' => 'Insufficient stock'
            ];
        }

        $amounts = $this->priceService->calculate(
            $productId,
            $quantity
        );

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
}
