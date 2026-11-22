<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WithoutDiController;
use App\Http\Controllers\WithDiController;
use App\OrderComparison\Lifetime\LifetimeService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/without-di', [WithoutDiController::class, 'index']);
Route::get('/with-di', [WithDiController::class, 'index']);

Route::get('/test-bind', function () {
    $service1 = app()->make(
        \App\Contracts\MessageServiceInterface::class
    );

    $service2 = app()->make(
        \App\Contracts\MessageServiceInterface::class
    );

    return [
        'same_instance' => $service1 === $service2,
        'service1_id' => spl_object_id($service1),
        'service2_id' => spl_object_id($service2),
    ]; 
});

// Comparison: direct instantiation (new)
Route::get('/orders/direct-new', [\App\OrderComparison\DirectNew\OrderController::class, 'index']);
Route::get(
    '/orders/direct-new/reservation',
    [\App\OrderComparison\DirectNew\OrderController::class, 'reservation']
);
Route::get(
    '/orders/direct-new/subscription',
    [\App\OrderComparison\DirectNew\OrderController::class, 'subscription']
);
// STEP10 Pure DI - Decorator
Route::get(
    '/orders/manual-di/logging',
    [\App\OrderComparison\ManualDi\OrderController::class, 'logging']
);


// Comparison: CI2-style shared superobject (not Laravel's container)
Route::get('/orders/super-object', [\App\OrderComparison\SuperObject\OrderController::class, 'index']);
Route::get(
    '/orders/super-object/reservation',
    [\App\OrderComparison\SuperObject\OrderController::class, 'reservation']
);
Route::get(
    '/orders/super-object/subscription',
    [\App\OrderComparison\SuperObject\OrderController::class, 'subscription']
);

// Comparison: manual constructor injection (no container resolution)
Route::get('/orders/manual-di', [\App\OrderComparison\ManualDi\OrderController::class, 'index']);
Route::get(
    '/orders/manual-di/reservation',
    [\App\OrderComparison\ManualDi\OrderController::class, 'reservation']
);
Route::get(
    '/orders/manual-di/subscription',
    [\App\OrderComparison\ManualDi\OrderController::class, 'subscription']
);

// Comparison: container auto-resolution with concrete classes
Route::get('/orders/container-concrete', [\App\OrderComparison\ContainerConcrete\OrderController::class, 'index']);
Route::get(
    '/orders/container-concrete/reservation',
    [\App\OrderComparison\ContainerConcrete\OrderController::class, 'reservation']
);
Route::get(
    '/orders/container-concrete/subscription',
    [\App\OrderComparison\ContainerConcrete\OrderController::class, 'subscription']
);

// Comparison: container resolution through interfaces
Route::get('/orders/container-interface', [\App\OrderComparison\ContainerInterface\OrderController::class, 'index']);
Route::get(
    '/orders/container-interface/reservation',
    [\App\OrderComparison\ContainerInterface\OrderController::class, 'reservation']
);
Route::get(
    '/orders/container-interface/subscription',
    [\App\OrderComparison\ContainerInterface\OrderController::class, 'subscription']
);
 // STEP11 DI Container - Decorator
Route::get(
    '/orders/container-interface/logging',
    [\App\OrderComparison\ContainerInterface\OrderController::class, 'logging']
);

// Comparison: inheritance (template method)
Route::get('/orders/inheritance', [\App\OrderComparison\Inheritance\OrderController::class, 'index']);
Route::get(
    '/orders/inheritance/reservation',
    [\App\OrderComparison\Inheritance\OrderController::class, 'reservation']
);
Route::get(
    '/orders/inheritance/subscription',
    [\App\OrderComparison\Inheritance\OrderController::class, 'subscription']
);

// Comparison: inheritance plus interface contract
Route::get('/orders/inheritance-interface', [\App\OrderComparison\InheritanceInterface\OrderController::class, 'index']);
Route::get(
    '/orders/inheritance-interface/reservation',
    [\App\OrderComparison\InheritanceInterface\OrderController::class, 'reservation']
);
Route::get(
    '/orders/trait/subscription',
    [\App\OrderComparison\Trait\OrderController::class, 'subscription']
);

// Comparison: trait-based workflow reuse
Route::get('/orders/trait', [\App\OrderComparison\Trait\OrderController::class, 'index']);
Route::get(
    '/orders/trait/reservation',
    [\App\OrderComparison\Trait\OrderController::class, 'reservation']
);
Route::get(
    '/orders/inheritance-interface/subscription',
    [\App\OrderComparison\InheritanceInterface\OrderController::class, 'subscription']
);

// STEP9 ライブタイムの確認


Route::get('/lifetime/bind', function () {
    $app = app();

    $app->bind(
        LifetimeService::class,
        LifetimeService::class
    );

    $first = $app->make(LifetimeService::class);
    $first->increment();

    $second = $app->make(LifetimeService::class);

    return response()->json([
        'same_instance' => $first === $second,
        'first_count' => $first->count,
        'second_count' => $second->count,
    ]);
});

Route::get('/lifetime/singleton', function () {
    $app = app();

    $app->singleton(
        LifetimeService::class,
        LifetimeService::class
    );

    $first = $app->make(LifetimeService::class);
    $first->increment();

    $second = $app->make(LifetimeService::class);

    return response()->json([
        'same_instance' => $first === $second,
        'first_count' => $first->count,
        'second_count' => $second->count,
    ]);
});

Route::get('/lifetime/scoped', function () {
    $app = app();

    $app->scoped(
        LifetimeService::class,
        LifetimeService::class
    );

    $first = $app->make(LifetimeService::class);
    $first->increment();

    $second = $app->make(LifetimeService::class);

    return response()->json([
        'same_instance' => $first === $second,
        'first_count' => $first->count,
        'second_count' => $second->count,
    ]);
});

Route::get('/lifetime/manual-di', function () {
    // インスタンスを1回だけ生成
    $service = new LifetimeService();

    // 同じインスタンスを2つの変数で共有
    $first = $service;
    $second = $service;

    $first->increment();

    return response()->json([
        'same_instance' => $first === $second,
        'first_count' => $first->count,
        'second_count' => $second->count,
    ]);
});

Route::get('/lifetime/scope-comparison', function () {
    // 検証専用のコンテナを作成
    $container = new \Illuminate\Container\Container();

    $container->singleton(
        'lifetime.singleton',
        LifetimeService::class
    );

    $container->scoped(
        'lifetime.scoped',
        LifetimeService::class
    );

    // 1回目の解決
    $singleton1 = $container->make('lifetime.singleton');
    $scoped1 = $container->make('lifetime.scoped');

    $singleton1->increment();
    $scoped1->increment();

    // スコープの終了を再現
    $container->forgetScopedInstances();

    // 2回目の解決
    $singleton2 = $container->make('lifetime.singleton');
    $scoped2 = $container->make('lifetime.scoped');

    return response()->json([
        'singleton' => [
            'same_instance' => $singleton1 === $singleton2,
            'count' => $singleton2->count,
        ],
        'scoped' => [
            'same_instance' => $scoped1 === $scoped2,
            'count' => $scoped2->count,
        ],
    ]);
});

// STEP12 Pure DI - Composition Root
$createOrderController = function () {

    // OrderServiceの生成処理を共通化
    $createOrderService = function (
        \App\OrderComparison\ManualDi\StockService $stockService
    ) {
        return new \App\OrderComparison\ManualDi\OrderService(
            $stockService,
            new \App\OrderComparison\ManualDi\DiscountPriceService(
                new \App\OrderComparison\ManualDi\MemberService()
            ),
            new \App\OrderComparison\ManualDi\OrderRepository(),
            new \App\OrderComparison\ServiceLocator\AuditLogService()
        );
    };

    // 通常注文だけFakeStockServiceを使用
    $normalOrderService = $createOrderService(
        new \App\OrderComparison\ManualDi\FakeStockService()
    );

    // 予約・定期・ログ付き注文
    $service = $createOrderService(
        new \App\OrderComparison\ManualDi\StockService()
    );

    // ログ付き注文にはデコレーターを追加
    $loggingService = new \App\OrderComparison\ManualDi\LoggingOrderService(
        $service
    );

    return new \App\OrderComparison\ManualDi\CompositionRootOrderController(
        $normalOrderService,
        $service,
        $loggingService
    );
};


// 通常注文
Route::get(
    '/orders/manual-di/composition-root',
    fn () => $createOrderController()->index()
);

// 予約注文
Route::get(
    '/orders/manual-di/composition-root/reservation',
    fn () => $createOrderController()->reservation()
);

// 定期注文
Route::get(
    '/orders/manual-di/composition-root/subscription',
    fn () => $createOrderController()->subscription()
);

// ログ付き注文
Route::get(
    '/orders/manual-di/composition-root/logging',
    fn () => $createOrderController()->logging()
);

Route::get('/orders/constructor-injection/missing', function () {
    $test = new \App\OrderComparison\ManualDi\ConstructorInjectionTest();

    return response()->json(
        $test->missingDependency()
    );
});

 // STEP13 コンストラクタ注入：依存先の型チェック
Route::get('/orders/constructor-injection/invalid-type', function () {
    $test = new \App\OrderComparison\ManualDi\ConstructorInjectionTest();

    return response()->json(
        $test->invalidDependencyType()
    );
});

 // STEP13 コンストラクタ注入：正常な依存関係
Route::get('/orders/constructor-injection/valid', function () {
    $test = new \App\OrderComparison\ManualDi\ConstructorInjectionTest();

    return response()->json(
        $test->validDependency()
    );
});

 // STEP13 コンストラクタ注入：依存先の差し替え
Route::get('/orders/constructor-injection/replace', function () {
    $test = new \App\OrderComparison\ManualDi\ConstructorInjectionTest();

    return response()->json(
        $test->replaceDependency()
    );
});

 // STEP14 メソッド経由での依存注入
Route::get('/orders/method-injection', function () {

    $service = new \App\OrderComparison\ManualDi\MethodInjectionOrderService(
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    // 1回目：通常の在庫確認
    $normal = $service->placeOrder(
        1001,
        2,
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\MemberService()
    );

    // 2回目：FakeStockService
    $fake = $service->placeOrder(
        1001,
        2,
        new \App\OrderComparison\ManualDi\FakeStockService(),
        new \App\OrderComparison\ManualDi\MemberService()
    );

    return response()->json([
        'normal' => $normal,
        'fake' => $fake
    ]);
});

// STEP15 プロパティ経由での依存注入
Route::get('/orders/property-injection', function () {

    // StockServiceを渡さずにオブジェクトを生成
    $service = new \App\OrderComparison\ManualDi\PropertyInjectionOrderService(
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    // オブジェクト生成後に依存サービスを設定
    $service->stockService =
        new \App\OrderComparison\ManualDi\StockService();

    // 注文処理を実行
    $result = $service->placeOrder(1001, 2);

    return response()->json($result);
});


// STEP15 プロパティ注入：依存サービスの設定漏れ
Route::get('/orders/property-injection/missing', function () {

    $service = new \App\OrderComparison\ManualDi\PropertyInjectionOrderService(
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    // StockServiceを設定しない
    // $service->stockService = new StockService();

    try {
        $result = $service->placeOrder(1001, 2);

        return response()->json($result);

    } catch (\Error $e) {

        return response()->json([
            'success' => false,
            'error' => get_class($e),
            'message' => $e->getMessage()
        ]);
    }
});

// STEP15 プロパティ注入：依存先の差し替え
Route::get('/orders/property-injection/replace', function () {

    $service = new \App\OrderComparison\ManualDi\PropertyInjectionOrderService(
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    // 1回目：通常の在庫確認
    $service->stockService =
        new \App\OrderComparison\ManualDi\StockService();

    $normal = $service->placeOrder(1001, 2);

    // 2回目：同じインスタンスの依存先を変更
    $service->stockService =
        new \App\OrderComparison\ManualDi\FakeStockService();

    $fake = $service->placeOrder(1001, 2);

    return response()->json([
        'normal' => $normal,
        'fake' => $fake
    ]);
});

// STEP16 注入パターンの選択：クーポン注文
Route::get('/orders/injection-selection/coupon', function () {

    $service = new \App\OrderComparison\ManualDi\CouponOrderService(
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    // 通常注文：CouponServiceは渡さない
    $normal = $service->placeOrder(1001, 2);

    // クーポン注文：CouponServiceをメソッド注入
    $coupon = $service->placeOrderWithCoupon(
        1001,
        2,
        'OFF500',
        new \App\OrderComparison\ManualDi\CouponService()
    );

    return response()->json([
        'normal' => $normal,
        'coupon' => $coupon
    ]);
});


 // STEP16 クーポンサービスの注入方式を比較
Route::get('/orders/injection-selection/compare', function () {

    // ① メソッド注入版
    $methodService = new \App\OrderComparison\ManualDi\CouponOrderService(
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    // ② コンストラクタ注入版
    $constructorService = new \App\OrderComparison\ManualDi\ConstructorCouponOrderService(
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ManualDi\CouponService()
    );

    // 同じ条件でクーポン注文を実行
    $method = $methodService->placeOrderWithCoupon(
        1001,
        2,
        'OFF500',
        new \App\OrderComparison\ManualDi\CouponService()
    );

    $constructor = $constructorService->placeOrderWithCoupon(
        1001,
        2,
        'OFF500'
    );

    return response()->json([
        'method_injection' => $method,
        'constructor_injection' => $constructor
    ]);
});

 // STEP16 OFF10の動作比較
Route::get('/orders/injection-selection/off10', function () {

    // メソッド注入版
    $methodService = new \App\OrderComparison\ManualDi\CouponOrderService(
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    // コンストラクタ注入版
    $constructorService = new \App\OrderComparison\ManualDi\ConstructorCouponOrderService(
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ManualDi\CouponService()
    );

    // 同じ条件で10%割引を適用
    $method = $methodService->placeOrderWithCoupon(
        1001,
        2,
        'OFF10',
        new \App\OrderComparison\ManualDi\CouponService()
    );

    $constructor = $constructorService->placeOrderWithCoupon(
        1001,
        2,
        'OFF10'
    );

    return response()->json([
        'method_injection' => $method,
        'constructor_injection' => $constructor
    ]);
});

 // STEP16 CouponServiceの実装差し替え比較
Route::get('/orders/injection-selection/replace', function () {

    $stockService = new \App\OrderComparison\ManualDi\StockService();

    $priceService = new \App\OrderComparison\ManualDi\DiscountPriceService(
        new \App\OrderComparison\ManualDi\MemberService()
    );

    $repository = new \App\OrderComparison\ManualDi\OrderRepository();

    $normalCoupon = new \App\OrderComparison\ManualDi\CouponService();
    $campaignCoupon = new \App\OrderComparison\ManualDi\CampaignCouponService();

    // ① メソッド注入：同じインスタンスを使用
    $methodService = new \App\OrderComparison\ManualDi\CouponOrderService(
        $stockService,
        $priceService,
        $repository
    );

    $methodNormal = $methodService->placeOrderWithCoupon(
        1001,
        2,
        'OFF500',
        $normalCoupon
    );

    $methodCampaign = $methodService->placeOrderWithCoupon(
        1001,
        2,
        'OFF500',
        $campaignCoupon
    );

    // ② コンストラクタ注入：依存先ごとにインスタンスを生成
    $constructorNormal = new \App\OrderComparison\ManualDi\ConstructorCouponOrderService(
        $stockService,
        $priceService,
        $repository,
        $normalCoupon
    );

    $constructorCampaign = new \App\OrderComparison\ManualDi\ConstructorCouponOrderService(
        $stockService,
        $priceService,
        $repository,
        $campaignCoupon
    );

    $constructorNormalResult = $constructorNormal->placeOrderWithCoupon(
        1001,
        2,
        'OFF500'
    );

    $constructorCampaignResult = $constructorCampaign->placeOrderWithCoupon(
        1001,
        2,
        'OFF500'
    );

    return response()->json([
        'method_injection' => [
            'normal' => $methodNormal,
            'campaign' => $methodCampaign
        ],
        'constructor_injection' => [
            'normal' => $constructorNormalResult,
            'campaign' => $constructorCampaignResult
        ]
    ]);
});

// STEP16 共通ファクトリによる生成処理の集約
foreach (['web', 'api', 'batch'] as $channel) {

    Route::get(
        "/orders/injection-selection/factory/{$channel}",
        function () use ($channel) {

            $service = \App\OrderComparison\ManualDi\CouponOrderServiceFactory::create(
                true
            );

            $result = $service->placeOrderWithCoupon(
                1001,
                2,
                'OFF500'
            );

            return response()->json([
                'channel' => $channel,
                'result' => $result
            ]);
        }
    );
}

// STEP16 メソッド注入：共通ファクトリによる生成処理の集約
// foreach (['web', 'api', 'batch'] as $channel) {

//     Route::get(
//         "/orders/injection-selection/method-factory/{$channel}",
//         function () use ($channel) {

//             // 注文サービスを生成
//             $service = \App\OrderComparison\ManualDi\MethodCouponOrderServiceFactory::create();

//             // クーポンサービスを生成
//             $couponService = \App\OrderComparison\ManualDi\MethodCouponOrderServiceFactory::createCouponService(
//                 true
//             );

//             // メソッド注入で注文を実行
//             $result = $service->placeOrderWithCoupon(
//                 1001,
//                 2,
//                 'OFF500',
//                 $couponService
//             );

//             return response()->json([
//                 'channel' => $channel,
//                 'result' => $result
//             ]);
//         }
//     );
// }

 // STEP16 コンストラクタ注入：キャンペーン選択の共通化
// foreach (['web', 'api', 'batch'] as $channel) {

//     Route::get(
//         "/orders/injection-selection/factory/{$channel}",
//         function () use ($channel) {

//             // キャンペーン状態はファクトリ側で判断
//             $service = \App\OrderComparison\ManualDi\CouponOrderServiceFactory::create();

//             $result = $service->placeOrderWithCoupon(
//                 1001,
//                 2,
//                 'OFF500'
//             );

//             return response()->json([
//                 'channel' => $channel,
//                 'result' => $result
//             ]);
//         }
//     );
// }

 // STEP16 メソッド注入：キャンペーン選択の共通化
foreach (['web', 'api', 'batch'] as $channel) {

    Route::get(
        "/orders/injection-selection/method-factory/{$channel}",
        function () use ($channel) {

            // 注文サービスを生成
            $service = \App\OrderComparison\ManualDi\MethodCouponOrderServiceFactory::create();

            // キャンペーン状態はファクトリ側で判断
            $couponService = \App\OrderComparison\ManualDi\MethodCouponOrderServiceFactory::createCouponService();

            // メソッド注入で注文を実行
            $result = $service->placeOrderWithCoupon(
                1001,
                2,
                'OFF500',
                $couponService
            );

            return response()->json([
                'channel' => $channel,
                'result' => $result
            ]);
        }
    );
}

// STEP16 注文ごとの依存先切り替え：メソッド注入
Route::get(
    '/orders/injection-selection/per-order/method',
    function () {

        // 注文サービスは1回だけ生成
        $service = \App\OrderComparison\ManualDi\MethodCouponOrderServiceFactory::create();

        // 1回目：通常版
        $normalResult = $service->placeOrderWithCoupon(
            1001,
            2,
            'OFF500',
            new \App\OrderComparison\ManualDi\CouponService()
        );

        // 2回目：キャンペーン版
        $campaignResult = $service->placeOrderWithCoupon(
            1001,
            2,
            'OFF500',
            new \App\OrderComparison\ManualDi\CampaignCouponService()
        );

        return response()->json([
            'normal' => $normalResult,
            'campaign' => $campaignResult
        ]);
    }
);

// STEP16 注文ごとの依存先切り替え：コンストラクタ注入
Route::get(
    '/orders/injection-selection/per-order/constructor',
    function () {

        // 共通の依存サービスを生成
        $stockService = new \App\OrderComparison\ManualDi\StockService();

        $priceService = new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        );

        $orderRepository = new \App\OrderComparison\ManualDi\OrderRepository();

        // 1回目：通常版の注文サービスを生成
        $normalService = new \App\OrderComparison\ManualDi\ConstructorCouponOrderService(
            $stockService,
            $priceService,
            $orderRepository,
            new \App\OrderComparison\ManualDi\CouponService()
        );

        $normalResult = $normalService->placeOrderWithCoupon(
            1001,
            2,
            'OFF500'
        );

        // 2回目：キャンペーン版の注文サービスを生成
        $campaignService = new \App\OrderComparison\ManualDi\ConstructorCouponOrderService(
            $stockService,
            $priceService,
            $orderRepository,
            new \App\OrderComparison\ManualDi\CampaignCouponService()
        );

        $campaignResult = $campaignService->placeOrderWithCoupon(
            1001,
            2,
            'OFF500'
        );

        return response()->json([
            'normal' => $normalResult,
            'campaign' => $campaignResult,
            'same_instance' => $normalService === $campaignService
        ]);
    }
);

/**
 * STEP17：コントロールフリーク
 * 依存先を注文サービス内部で生成する
 */
Route::get(
    '/orders/control-freak',
    function () {
        $service = new \App\OrderComparison\ControlFreak\ControlFreakOrderService();

        return response()->json(
            $service->placeOrder(1001, 10)
        );
    }
);

/**
 * STEP17：Manual DIによる依存先差し替え
 */
Route::get('/orders/manual-di/test-stock', function () {
    $service = new \App\OrderComparison\ManualDi\OrderService(
        new \App\OrderComparison\ControlFreak\WarehouseStockService(
            new \App\OrderComparison\ControlFreak\WarehouseConfig(1)
        ),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    return response()->json(
        $service->placeOrder(1001, 10)
    );
});


/**
 * STEP17：ControlFreak 複数経路の検証
 *
 * Web・API・バッチをHTTPルートで模擬する。
 */
foreach (['web', 'api', 'batch'] as $channel) {
    Route::get(
        "/orders/control-freak/channel/{$channel}",
        function () use ($channel) {
            $service = new \App\OrderComparison\ControlFreak\ControlFreakOrderService();

            return response()->json([
                'channel' => $channel,
                'result' => $service->placeOrder(1001, 10),
            ]);
        }
    );
}


/**
 * STEP17：Manual DI 複数経路の検証
 *
 * Web・API・バッチをHTTPルートで模擬する。
 * 経路ごとに異なる倉庫を選択する。
 */
foreach ([
    'web' => 1,
    'api' => 2,
    'batch' => 1,
] as $channel => $warehouseId) {
    Route::get(
        "/orders/manual-di/channel/{$channel}",
        function () use ($channel, $warehouseId) {
            $service = new \App\OrderComparison\ManualDi\OrderService(
                new \App\OrderComparison\ControlFreak\WarehouseStockService(
                    new \App\OrderComparison\ControlFreak\WarehouseConfig($warehouseId)
                ),
                new \App\OrderComparison\ManualDi\DiscountPriceService(
                    new \App\OrderComparison\ManualDi\MemberService()
                ),
                new \App\OrderComparison\ManualDi\OrderRepository(),
                new \App\OrderComparison\ServiceLocator\AuditLogService()
            );

            return response()->json([
                'channel' => $channel,
                'result' => $service->placeOrder(1001, 10),
            ]);
        }
    );
}

/**
 * STEP17：ControlFreak改善版の動作確認
 *
 * 依存先を外部で生成し、
 * コンストラクタから注入する。
 */
Route::get('/orders/control-freak/improved/{warehouseId}', function (int $warehouseId) {
    $service = new \App\OrderComparison\ControlFreak\ImprovedOrderService(
        new \App\OrderComparison\ControlFreak\WarehouseStockService(
            new \App\OrderComparison\ControlFreak\WarehouseConfig($warehouseId)
        ),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository()
    );

    return response()->json(
        $service->placeOrder(1001, 10)
    );
});

/**
 * STEP18：サービスロケータ方式の動作確認
 */
Route::get('/orders/service-locator', function () {
    $service = new \App\OrderComparison\ServiceLocator\ServiceLocatorOrderService();

    return response()->json(
        $service->placeOrder(1001, 2)
    );
});

/**
 * STEP18：サービスロケータの依存先差し替え
 */
Route::get('/orders/service-locator/test-stock', function () {
    app()->bind(
        \App\OrderComparison\ManualDi\StockService::class,
        \App\OrderComparison\ControlFreak\TestStockService::class
    );

    $service = new \App\OrderComparison\ServiceLocator\ServiceLocatorOrderService();

    return response()->json(
        $service->placeOrder(1001, 11)
    );
});

/**
 * STEP18：コンテナ登録変更の影響範囲
 */
Route::get('/orders/service-locator/binding-impact', function () {

    // ① 通常の依存先を登録
    app()->bind(
        \App\OrderComparison\ManualDi\StockService::class,
        \App\OrderComparison\ManualDi\StockService::class
    );

    // ② 登録変更前に注文サービスを生成
    $beforeService = new \App\OrderComparison\ServiceLocator\ServiceLocatorOrderService();

    // ③ 在庫サービスの登録を変更
    app()->bind(
        \App\OrderComparison\ManualDi\StockService::class,
        \App\OrderComparison\ControlFreak\TestStockService::class
    );

    // ④ 登録変更後に注文サービスを生成
    $afterService = new \App\OrderComparison\ServiceLocator\ServiceLocatorOrderService();

    return response()->json([
        'before' => $beforeService->placeOrder(1001, 11),
        'after' => $afterService->placeOrder(1001, 11),
    ]);
});

/**
 * STEP18：コンテナ登録変更による別処理への影響
 */
Route::get('/orders/service-locator/shared-binding', function () {

    // 通常の在庫サービスを登録
    app()->bind(
        \App\OrderComparison\ManualDi\StockService::class,
        \App\OrderComparison\ManualDi\StockService::class
    );

    // 特別注文用に在庫サービスを差し替える
    app()->bind(
        \App\OrderComparison\ManualDi\StockService::class,
        \App\OrderComparison\ControlFreak\TestStockService::class
    );

    // 特別注文
    $specialService = new \App\OrderComparison\ServiceLocator\ServiceLocatorOrderService();

    // 通常注文（本来は通常の在庫確認を行いたい）
    $normalService = new \App\OrderComparison\ServiceLocator\ServiceLocatorOrderService();

    return response()->json([
        'special_order' => $specialService->placeOrder(1001, 11),
        'normal_order' => $normalService->placeOrder(1001, 11),
    ]);
});

/**
 * STEP18：Manual DIによる業務別の依存先指定
 */
Route::get('/orders/manual-di/shared-binding', function () {

    // 特別注文：テスト用在庫サービスを使用
    $specialService = new \App\OrderComparison\ManualDi\OrderService(
        new \App\OrderComparison\ControlFreak\TestStockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    // 通常注文：通常の在庫サービスを使用
    $normalService = new \App\OrderComparison\ManualDi\OrderService(
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    return response()->json([
        'special_order' => $specialService->placeOrder(1001, 11),
        'normal_order' => $normalService->placeOrder(1001, 11),
    ]);
});

/**
 * STEP18：サービス生成後の依存先差し替え
 */
Route::get('/orders/service-locator/late-binding', function () {

    // ① 通常の在庫サービスを登録
    app()->bind(
        \App\OrderComparison\ManualDi\StockService::class,
        \App\OrderComparison\ManualDi\StockService::class
    );

    // ② 注文サービスを生成
    $service = new \App\OrderComparison\ServiceLocator\ServiceLocatorOrderService();

    // ③ 注文サービス生成後に登録を変更
    app()->bind(
        \App\OrderComparison\ManualDi\StockService::class,
        \App\OrderComparison\ControlFreak\TestStockService::class
    );

    // ④ 11個の注文を実行
    return response()->json(
        $service->placeOrder(1001, 11)
    );
});

/**
 * STEP18：コンストラクタ注入による業務別の依存先指定
 */
Route::get('/orders/service-locator/improved', function () {

    // 特別注文：テスト用在庫サービスを注入
    $specialService = new \App\OrderComparison\ServiceLocator\ImprovedOrderService(
        new \App\OrderComparison\ControlFreak\TestStockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    // 通常注文：通常の在庫サービスを注入
    $normalService = new \App\OrderComparison\ServiceLocator\ImprovedOrderService(
        new \App\OrderComparison\ManualDi\StockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    return response()->json([
        'special_order' => $specialService->placeOrder(1001, 11),
        'normal_order' => $normalService->placeOrder(1001, 11),
    ]);
});

Route::get('/orders/ambient-context/basic', function () {
    $stockService = new \App\OrderComparison\AmbientContext\AmbientStockService();

    $orderService = new \App\OrderComparison\ManualDi\OrderService(
        $stockService,
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    return response()->json(
        $orderService->placeOrder(1001, 6)
    );
});


Route::get('/orders/ambient-context/warehouse2', function () {
    \App\OrderComparison\AmbientContext\WarehouseContext::setWarehouseId(2);

    $stockService = new \App\OrderComparison\AmbientContext\AmbientStockService();

    $orderService = new \App\OrderComparison\ManualDi\OrderService(
        $stockService,
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    return response()->json(
        $orderService->placeOrder(1001, 6)
    );
});

Route::get('/orders/ambient-context/shared', function () {
    $context = \App\OrderComparison\AmbientContext\WarehouseContext::class;

    // 倉庫1を設定
    $context::setWarehouseId(1);

    // 注文サービスAを生成
    $orderServiceA = new \App\OrderComparison\ManualDi\OrderService(
        new \App\OrderComparison\AmbientContext\AmbientStockService(),
        new \App\OrderComparison\ManualDi\DiscountPriceService(
            new \App\OrderComparison\ManualDi\MemberService()
        ),
        new \App\OrderComparison\ManualDi\OrderRepository(),
        new \App\OrderComparison\ServiceLocator\AuditLogService()
    );

    // 注文サービスAの生成後、倉庫2に変更
    $context::setWarehouseId(2);

    // 注文サービスAで注文
    $result = $orderServiceA->placeOrder(1001, 6);

    return response()->json([
        'current_warehouse_id' => $context::getWarehouseId(),
        'order_a' => $result,
    ]);
});

Route::get('/orders/ambient-context/multiple', function () {
    $context = \App\OrderComparison\AmbientContext\WarehouseContext::class;

    $createOrderService = function () {
        return new \App\OrderComparison\ManualDi\OrderService(
            new \App\OrderComparison\AmbientContext\AmbientStockService(),
            new \App\OrderComparison\ManualDi\DiscountPriceService(
                new \App\OrderComparison\ManualDi\MemberService()
            ),
            new \App\OrderComparison\ManualDi\OrderRepository(),
            new \App\OrderComparison\ServiceLocator\AuditLogService()
        );
    };

    // 倉庫1を設定して注文サービスAを生成
    $context::setWarehouseId(1);
    $orderServiceA = $createOrderService();

    // 倉庫2を設定して注文サービスBを生成
    $context::setWarehouseId(2);
    $orderServiceB = $createOrderService();

    // それぞれの注文サービスで注文
    $resultA = $orderServiceA->placeOrder(1001, 6);
    $resultB = $orderServiceB->placeOrder(1001, 6);

    return response()->json([
        'current_warehouse_id' => $context::getWarehouseId(),
        'order_a' => $resultA,
        'order_b' => $resultB,
    ]);
});

Route::get('/orders/ambient-context/explicit', function () {
    $createOrderService = function (int $warehouseId) {
        $warehouseConfig =
            new \App\OrderComparison\ControlFreak\WarehouseConfig(
                $warehouseId
            );

        $stockService =
            new \App\OrderComparison\ControlFreak\WarehouseStockService(
                $warehouseConfig
            );

        return new \App\OrderComparison\ManualDi\OrderService(
            $stockService,
            new \App\OrderComparison\ManualDi\DiscountPriceService(
                new \App\OrderComparison\ManualDi\MemberService()
            ),
            new \App\OrderComparison\ManualDi\OrderRepository(),
            new \App\OrderComparison\ServiceLocator\AuditLogService()
        );
    };

    // 倉庫1用の注文サービス
    $orderServiceA = $createOrderService(1);

    // 倉庫2用の注文サービス
    $orderServiceB = $createOrderService(2);

    return response()->json([
        'order_a' => $orderServiceA->placeOrder(1001, 6),
        'order_b' => $orderServiceB->placeOrder(1001, 6),
    ]);
});

Route::get('/orders/ambient-context/test-order', function () {
    $context = \App\OrderComparison\AmbientContext\WarehouseContext::class;

    $stockService =
        new \App\OrderComparison\AmbientContext\AmbientStockService();

    // テストA：倉庫2の在庫不足を確認
    $context::setWarehouseId(2);
    $testA = $stockService->hasStock(1001, 6);

    // テストB：倉庫1の在庫確認を想定
    // ただし、倉庫IDを明示的に初期化していない
    $testB = $stockService->hasStock(1001, 6);

    // テストBを独立して実行する場合の想定
    $context::setWarehouseId(1);
    $testBIndependent = $stockService->hasStock(1001, 6);

    return response()->json([
        'test_a' => $testA,
        'test_b_after_a' => $testB,
        'test_b_with_reset' => $testBIndependent,
    ]);
});

Route::get('/orders/ambient-context/restore', function () {
    $context = \App\OrderComparison\AmbientContext\WarehouseContext::class;

    $stockService =
        new \App\OrderComparison\AmbientContext\AmbientStockService();

    // 最初の倉庫ID
    $context::setWarehouseId(1);

    $before = $stockService->hasStock(1001, 6);

    // 現在の状態を保存
    $originalWarehouseId = $context::getWarehouseId();

    try {
        // 一時的に倉庫2へ変更
        $context::setWarehouseId(2);

        $during = $stockService->hasStock(1001, 6);
    } finally {
        // 元の倉庫IDへ戻す
        $context::setWarehouseId($originalWarehouseId);
    }

    $after = $stockService->hasStock(1001, 6);

    return response()->json([
        'before' => $before,
        'during' => $during,
        'after' => $after,
        'current_warehouse_id' => $context::getWarehouseId(),
    ]);
});

Route::get('/orders/ambient-context/exception', function () {
    $context = \App\OrderComparison\AmbientContext\WarehouseContext::class;

    $context::setWarehouseId(1);

    $originalWarehouseId = $context::getWarehouseId();

    $caughtMessage = null;

    try {
        $context::setWarehouseId(2);

        // 意図的に例外を発生させる
        throw new \RuntimeException('Test exception');
    } catch (\RuntimeException $e) {
        $caughtMessage = $e->getMessage();
    } finally {
        $context::setWarehouseId($originalWarehouseId);
    }

    return response()->json([
        'exception_message' => $caughtMessage,
        'current_warehouse_id' => $context::getWarehouseId(),
        'stock_available' =>
            (new \App\OrderComparison\AmbientContext\AmbientStockService())
                ->hasStock(1001, 6),
    ]);
});

Route::get('/orders/ambient-context/scoped', function () {
    $contextA = app(
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class
    );

    // コンテキストAの倉庫IDを変更
    $contextA->setWarehouseId(2);

    // 同じリクエスト内で再取得
    $contextB = app(
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class
    );

    return response()->json([
        'same_instance' => $contextA === $contextB,
        'warehouse_a' => $contextA->getWarehouseId(),
        'warehouse_b' => $contextB->getWarehouseId(),
    ]);
});

Route::get('/orders/ambient-context/scoped-reset', function () {
    $container = app();

    $contextA = $container->make(
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class
    );

    $contextA->setWarehouseId(2);

    $before = $contextA->getWarehouseId();

    // scopedインスタンスを破棄
    $container->forgetScopedInstances();

    $contextB = $container->make(
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class
    );

    return response()->json([
        'before_reset' => $before,
        'after_reset' => $contextB->getWarehouseId(),
        'same_instance' => $contextA === $contextB,
    ]);
});

Route::get('/orders/ambient-context/scoped-reference', function () {
    $container = app();

    $contextA = $container->make(
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class
    );

    $contextA->setWarehouseId(2);

    // コンテナが保持するscopedインスタンスを破棄
    $container->forgetScopedInstances();

    // 新しいインスタンスを取得
    $contextB = $container->make(
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class
    );

    return response()->json([
        'old_instance_warehouse' => $contextA->getWarehouseId(),
        'new_instance_warehouse' => $contextB->getWarehouseId(),
        'same_instance' => $contextA === $contextB,
    ]);
});

Route::get('/orders/ambient-context/scoped-stock', function () {
    $container = app();

    $contextClass =
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class;

    $stockService =
        new \App\OrderComparison\AmbientContext\ScopedAmbientStockService();

    // 倉庫1で在庫確認
    $context = $container->make($contextClass);
    $context->setWarehouseId(1);

    $warehouse1 = $stockService->hasStock(1001, 6);

    // 同じスコープ内で倉庫2に変更
    $context->setWarehouseId(2);

    $warehouse2 = $stockService->hasStock(1001, 6);

    // scopedインスタンスの保持を解除
    $container->forgetScopedInstances();

    // 新しいコンテキストで在庫確認
    $afterReset = $stockService->hasStock(1001, 6);

    $newContext = $container->make($contextClass);

    return response()->json([
        'warehouse1' => $warehouse1,
        'warehouse2' => $warehouse2,
        'after_reset' => $afterReset,
        'current_warehouse_id' => $newContext->getWarehouseId(),
        'same_context_instance' => $context === $newContext,
    ]);
});

Route::get('/orders/ambient-context/injection-compare', function () {
    $container = app();

    $contextClass =
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class;

    $context = $container->make($contextClass);
    $context->setWarehouseId(2);

    // 実行時にコンテキストを取得する方式
    $ambientService =
        new \App\OrderComparison\AmbientContext\ScopedAmbientStockService();

    // コンストラクタでコンテキストを受け取る方式
    $injectedService =
        new \App\OrderComparison\AmbientContext\InjectedScopedStockService(
            $context
        );

    // スコープ初期化前
    $beforeAmbient = $ambientService->hasStock(1001, 6);
    $beforeInjected = $injectedService->hasStock(1001, 6);

    // コンテナが保持するscopedインスタンスを解除
    $container->forgetScopedInstances();

    // スコープ初期化後
    $afterAmbient = $ambientService->hasStock(1001, 6);
    $afterInjected = $injectedService->hasStock(1001, 6);

    $newContext = $container->make($contextClass);

    return response()->json([
        'before_reset' => [
            'ambient' => $beforeAmbient,
            'injected' => $beforeInjected,
        ],
        'after_reset' => [
            'ambient' => $afterAmbient,
            'injected' => $afterInjected,
        ],
        'old_context_warehouse' => $context->getWarehouseId(),
        'new_context_warehouse' => $newContext->getWarehouseId(),
    ]);
});

Route::get('/orders/ambient-context/http-lifecycle/{action}', function (string $action) {
    $context = app(
        \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class
    );

    if ($action === 'set') {
        $context->setWarehouseId(2);
    } elseif ($action !== 'get') {
        return response()->json([
            'success' => false,
            'message' => 'Invalid action',
        ], 400);
    }

    return response()->json([
        'action' => $action,
        'warehouse_id' => $context->getWarehouseId(),
    ]);
});
