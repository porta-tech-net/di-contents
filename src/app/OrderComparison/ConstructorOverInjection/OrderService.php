<?php

namespace App\OrderComparison\ConstructorOverInjection;

use App\OrderComparison\ManualDi\StockService;
use App\OrderComparison\ManualDi\DiscountPriceService;
use App\OrderComparison\ManualDi\OrderRepository;
use App\OrderComparison\ServiceLocator\AuditLogService;

class OrderService
{
    public function __construct(
        private StockService $stockService,
        private DiscountPriceService $priceService,
        private OrderRepository $orderRepository,
        private AuditLogService $auditLogService,
        private PaymentService $paymentService,
        private ShippingService $shippingService,
        private NotificationService $notificationService,
        private PointService $pointService,
        private FraudCheckService $fraudCheckService
    ) {
    }

    public function placeOrder(
        int $productId,
        int $quantity
    ): array {
        if ($quantity > 5) {
            return [
                'success' => false,
                'message' => 'Order quantity limit exceeded'
            ];
        }
        
        if (! $this->stockService->hasStock(
            $productId,
            $quantity
        )) {
            return [
                'success' => false,
                'message' => 'Insufficient stock'
            ];
        }

        $amounts = $this->priceService->calculate(
            $productId,
            $quantity
        );

        if ($this->fraudCheckService->isSuspicious(
            $productId,
            $quantity,
            $amounts['total']
        )) {
            return [
                'success' => false,
                'message' => 'Suspicious order detected'
            ];
        }

        $payment = $this->paymentService->charge(
            $amounts['total']
        );

        if (! $payment['success']) {
            return [
                'success' => false,
                'message' => 'Payment failed'
            ];
        }

        $order = $this->orderRepository->save(
            $productId,
            $quantity,
            $amounts['subtotal'],
            $amounts['tax'],
            $amounts['total']
        );

        $shipping = $this->shippingService->arrange(
            $productId,
            $quantity
        );

        $auditLog = $this->auditLogService->record(
            $productId,
            $quantity
        );

        $notification = $this->notificationService->notify(
            $productId,
            $quantity
        );

        $points = $this->pointService->grant(
            $amounts['total']
        );

        return [
            'success' => true,
            'order' => $order,
            'audit_log' => $auditLog,
            'payment' => $payment,
            'shipping' => $shipping,
            'notification' => $notification,
            'points' => $points,
        ];
    }
}