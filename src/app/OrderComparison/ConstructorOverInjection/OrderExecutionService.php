<?php

namespace App\OrderComparison\ConstructorOverInjection;

use App\OrderComparison\ManualDi\OrderRepository;

class OrderExecutionService
{
    public function __construct(
        private PaymentService $paymentService,
        private OrderRepository $orderRepository
    ) {
    }

    public function execute(
        int $productId,
        int $quantity,
        array $amounts
    ): array {
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

        return [
            'success' => true,
            'order' => $order,
            'payment' => $payment
        ];
    }
}