<?php

namespace App\OrderComparison\ConstructorOverInjection;

class OrderCompletionService
{
    public function __construct(
        private ShippingService $shippingService,
        private NotificationService $notificationService,
        private PointService $pointService
    ) {
    }

    public function arrangeShipping(
        int $productId,
        int $quantity
    ): array {
        return $this->shippingService->arrange(
            $productId,
            $quantity
        );
    }

    public function complete(
        int $productId,
        int $quantity,
        int $amount
    ): array {
        $notification = $this->notificationService->notify(
            $productId,
            $quantity
        );

        $points = $this->pointService->grant(
            $amount
        );

        return [
            'notification' => $notification,
            'points' => $points
        ];
    }
}