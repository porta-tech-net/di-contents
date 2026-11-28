<?php

namespace App\OrderComparison\ConstructorOverInjection;

class GroupedOrderService
{
    public function __construct(
        private OrderDependencies $dependencies
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
        
        if (! $this->dependencies->stockService->hasStock(
            $productId,
            $quantity
        )) {
            return [
                'success' => false,
                'message' => 'Insufficient stock'
            ];
        }

        $amounts = $this->dependencies->priceService->calculate(
            $productId,
            $quantity
        );

        if ($this->dependencies->fraudCheckService->isSuspicious(
            $productId,
            $quantity,
            $amounts['total']
        )) {
            return [
                'success' => false,
                'message' => 'Suspicious order detected'
            ];
        }

        $payment = $this->dependencies->paymentService->charge(
            $amounts['total']
        );

        if (! $payment['success']) {
            return [
                'success' => false,
                'message' => 'Payment failed'
            ];
        }

        $order = $this->dependencies->orderRepository->save(
            $productId,
            $quantity,
            $amounts['subtotal'],
            $amounts['tax'],
            $amounts['total']
        );

        $shipping = $this->dependencies->shippingService->arrange(
            $productId,
            $quantity
        );

        $auditLog = $this->dependencies->auditLogService->record(
            $productId,
            $quantity
        );

        $notification = $this->dependencies->notificationService->notify(
            $productId,
            $quantity
        );

        $points = $this->dependencies->pointService->grant(
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