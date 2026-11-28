<?php

namespace App\OrderComparison\ConstructorOverInjection;

use App\OrderComparison\ManualDi\StockService;
use App\OrderComparison\ManualDi\DiscountPriceService;

class OrderValidationService
{
    public function __construct(
        private StockService $stockService,
        private DiscountPriceService $priceService,
        private FraudCheckService $fraudCheckService
    ) {
    }

    public function validate(
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

        return [
            'success' => true,
            'amounts' => $amounts
        ];
    }
}