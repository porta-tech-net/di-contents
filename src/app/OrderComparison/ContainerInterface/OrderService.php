<?php

namespace App\OrderComparison\ContainerInterface;

class OrderService
{
    public function __construct(
        private StockServiceInterface $stockService,
        private PriceServiceInterface $priceService,
        private OrderRepositoryInterface $orderRepository
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
