<?php

namespace App\OrderComparison\ServiceLocator;

use App\OrderComparison\ManualDi\StockService;
use App\OrderComparison\ManualDi\DiscountPriceService;
use App\OrderComparison\ManualDi\OrderRepository;

class ServiceLocatorOrderService
{
    private StockService $stockService;
    private DiscountPriceService $priceService;
    private OrderRepository $orderRepository;
    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->stockService = app(StockService::class);

        $this->priceService = app(DiscountPriceService::class);

        $this->orderRepository = app(OrderRepository::class);

        $this->auditLogService = app(AuditLogService::class);
    }

    public function placeOrder(
        int $productId,
        int $quantity,
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
            'audit_log' => $this->auditLogService->record(
                $productId,
                $quantity
            ),
        ];
    }
}
