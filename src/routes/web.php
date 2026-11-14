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