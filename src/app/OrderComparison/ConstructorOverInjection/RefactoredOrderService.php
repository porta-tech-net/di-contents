<?php

namespace App\OrderComparison\ConstructorOverInjection;

use App\OrderComparison\ServiceLocator\AuditLogService;

class RefactoredOrderService
{
    public function __construct(
        private OrderValidationService $validationService,
        private OrderExecutionService $executionService,
        private OrderCompletionService $completionService,
        private AuditLogService $auditLogService
    ) {
    }

    public function placeOrder(
        int $productId,
        int $quantity
    ): array {
        $validation = $this->validationService->validate(
            $productId,
            $quantity
        );

        if (! $validation['success']) {
            return $validation;
        }

        $amounts = $validation['amounts'];

        $execution = $this->executionService->execute(
            $productId,
            $quantity,
            $amounts
        );

        if (! $execution['success']) {
            return $execution;
        }

        $shipping = $this->completionService->arrangeShipping(
            $productId,
            $quantity
        );

        $auditLog = $this->auditLogService->record(
            $productId,
            $quantity
        );

        $completion = $this->completionService->complete(
            $productId,
            $quantity,
            $amounts['total']
        );

        return [
            'success' => true,
            'order' => $execution['order'],
            'audit_log' => $auditLog,
            'payment' => $execution['payment'],
            'shipping' => $shipping,
            'notification' => $completion['notification'],
            'points' => $completion['points'],
        ];
    }
}