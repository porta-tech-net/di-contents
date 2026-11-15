<?php

namespace App\OrderComparison\ManualDi;

class ConstructorInjectionTest
{
    public function missingDependency(): array
    {
        try {
            $service = new OrderService();

            return [
                'success' => true,
                'message' => 'Object created'
            ];
        } catch (\ArgumentCountError $e) {
            return [
                'success' => false,
                'error' => get_class($e),
                'message' => $e->getMessage()
            ];
        }
    }

    public function invalidDependencyType(): array
    {
        try {
            $service = new OrderService(
                new MemberService(), // StockServiceではない
                new DiscountPriceService(new MemberService()),
                new OrderRepository()
            );

            return [
                'success' => true,
                'message' => 'Object created'
            ];
        } catch (\TypeError $e) {
            return [
                'success' => false,
                'error' => get_class($e),
                'message' => $e->getMessage()
            ];
        }
    }

    public function validDependency(): array
    {
        $service = new OrderService(
            new StockService(),
            new DiscountPriceService(
                new MemberService()
            ),
            new OrderRepository()
        );

        return [
            'success' => true,
            'message' => 'Object created',
            'order' => $service->placeOrder(1001, 2)
        ];
    }

    public function replaceDependency(): array
    {
        // 通常の依存先
        $normalService = new OrderService(
            new StockService(),
            new DiscountPriceService(
                new MemberService()
            ),
            new OrderRepository()
        );

        // FakeStockServiceに差し替え
        $fakeService = new OrderService(
            new FakeStockService(),
            new DiscountPriceService(
                new MemberService()
            ),
            new OrderRepository()
        );

        return [
            'normal' => $normalService->placeOrder(1001, 2),
            'fake' => $fakeService->placeOrder(1001, 2)
        ];
    }   

}
