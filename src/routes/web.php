<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WithoutDiController;
use App\Http\Controllers\WithDiController;

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

// Comparison: CI2-style shared superobject (not Laravel's container)
Route::get('/orders/super-object', [\App\OrderComparison\SuperObject\OrderController::class, 'index']);
Route::get(
    '/orders/super-object/reservation',
    [\App\OrderComparison\SuperObject\OrderController::class, 'reservation']
);

// Comparison: manual constructor injection (no container resolution)
Route::get('/orders/manual-di', [\App\OrderComparison\ManualDi\OrderController::class, 'index']);
Route::get(
    '/orders/manual-di/reservation',
    [\App\OrderComparison\ManualDi\OrderController::class, 'reservation']
);

// Comparison: container auto-resolution with concrete classes
Route::get('/orders/container-concrete', [\App\OrderComparison\ContainerConcrete\OrderController::class, 'index']);
Route::get(
    '/orders/container-concrete/reservation',
    [\App\OrderComparison\ContainerConcrete\OrderController::class, 'reservation']
);

// Comparison: container resolution through interfaces
Route::get('/orders/container-interface', [\App\OrderComparison\ContainerInterface\OrderController::class, 'index']);
Route::get(
    '/orders/container-interface/reservation',
    [\App\OrderComparison\ContainerInterface\OrderController::class, 'reservation']
);

// Comparison: inheritance (template method)
Route::get('/orders/inheritance', [\App\OrderComparison\Inheritance\OrderController::class, 'index']);
Route::get(
    '/orders/inheritance/reservation',
    [\App\OrderComparison\Inheritance\OrderController::class, 'reservation']
);

// Comparison: inheritance plus interface contract
Route::get('/orders/inheritance-interface', [\App\OrderComparison\InheritanceInterface\OrderController::class, 'index']);
Route::get(
    '/orders/inheritance-interface/reservation',
    [\App\OrderComparison\InheritanceInterface\OrderController::class, 'reservation']
);

// Comparison: trait-based workflow reuse
Route::get('/orders/trait', [\App\OrderComparison\Trait\OrderController::class, 'index']);
Route::get(
    '/orders/trait/reservation',
    [\App\OrderComparison\Trait\OrderController::class, 'reservation']
);