<?php

namespace App\OrderComparison\ControlFreak;

use App\OrderComparison\ManualDi\StockService;
use App\OrderComparison\ManualDi\MemberService;
use App\OrderComparison\ManualDi\DiscountPriceService;
use App\OrderComparison\ManualDi\OrderRepository;

class ControlFreakOrderService
{
    private StockService $stockService;
    private DiscountPriceService $priceService;
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->stockService =  new WarehouseStockService(
                                    new WarehouseConfig(2)
                                );

        $this->priceService = new DiscountPriceService(
            new MemberService()
        );

        $this->orderRepository = new OrderRepository();
    }

    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array {
        if ($orderType === 'reservation' && $quantity > 5) {
            return [
                'success' => false,
                'message' => 'Reservation quantity limit exceeded',
            ];
        }

        if (
            $orderType !== 'reservation'
            && ! $this->stockService->hasStock($productId, $quantity)
        ) {
            return [
                'success' => false,
                'message' => 'Insufficient stock',
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
