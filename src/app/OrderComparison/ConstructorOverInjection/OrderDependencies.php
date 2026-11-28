<?php

namespace App\OrderComparison\ConstructorOverInjection;

use App\OrderComparison\ManualDi\StockService;
use App\OrderComparison\ManualDi\DiscountPriceService;
use App\OrderComparison\ManualDi\OrderRepository;
use App\OrderComparison\ServiceLocator\AuditLogService;

class OrderDependencies
{
    public function __construct(
        public readonly StockService $stockService,
        public readonly DiscountPriceService $priceService,
        public readonly OrderRepository $orderRepository,
        public readonly AuditLogService $auditLogService,
        public readonly PaymentService $paymentService,
        public readonly ShippingService $shippingService,
        public readonly NotificationService $notificationService,
        public readonly PointService $pointService,
        public readonly FraudCheckService $fraudCheckService
    ) {
    }
}