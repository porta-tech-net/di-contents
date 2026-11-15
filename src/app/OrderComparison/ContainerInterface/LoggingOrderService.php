<?php

namespace App\OrderComparison\ContainerInterface;

use Illuminate\Support\Facades\Log;

class LoggingOrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderServiceInterface $inner
    ) {
    }

    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array {
        Log::info('Order started', [
            'product_id' => $productId,
            'quantity' => $quantity,
            'order_type' => $orderType,
        ]);

        try {
            $result = $this->inner->placeOrder(
                $productId,
                $quantity,
                $orderType
            );

            Log::info('Order finished', [
                'success' => $result['success'],
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::error('Order failed', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
