<?php

namespace App\OrderComparison\ManualDi;

use Illuminate\Http\JsonResponse;

class OrderController
{
    public function index(): JsonResponse
    {
        $stock = new StockService();
        $price = new DiscountPriceService();
        $repository = new OrderRepository();

        $service = new OrderService($stock, $price, $repository);

        return response()->json($service->placeOrder(1001, 2));
    }

    public function reservation(): JsonResponse
    {
        $service = new OrderService(
            new StockService(),
            new DiscountPriceService(),
            new OrderRepository()
        );

        return response()->json(
            $service->placeOrder(1001, 2, 'reservation')
        );
    }
}
