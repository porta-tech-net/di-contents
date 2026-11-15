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


