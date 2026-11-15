<?php

namespace App\OrderComparison\ManualDi;

class MethodInjectionOrderService
{
    public function __construct(
        private DiscountPriceService $priceService,
        private OrderRepository $orderRepository
    ) {
    }

    public function placeOrder(
        int $productId,
        int $quantity,
        StockService $stockService,
        MemberService $memberService,
        string $orderType = 'normal'
    ): array {
        if ($orderType === 'reservation' && $quantity > 5) {
            return [
                'success' => false,
                'message' => 'Reservation quantity limit exceeded'
            ];
        }

        if (
            $orderType !== 'reservation'
            && ! $stockService->hasStock($productId, $quantity)
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
