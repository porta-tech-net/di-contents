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
            new \App\OrderComparison\ManualDi\OrderRepository()
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

