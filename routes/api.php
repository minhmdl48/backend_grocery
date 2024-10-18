<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api', 'prefix' => '/v1/auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('profile', 'profile');
            Route::post('update/{id}', 'update');
            Route::group(['middleware' => 'admin'], function () {
                Route::get('index', 'index');
                Route::post('create', 'create');
                Route::get('delete/{id}', 'delete');
                Route::get('edit/{id}', 'edit');
            });
            Route::post('favourite/{id}', 'favourite');
            Route::get('cart', 'cart');
            Route::get("order-history", 'orderHistory');
            Route::get("order-history-cms", 'orderHistoryCms');
            Route::post("update-order-status", 'updateOrderStatus');
        });
    });
});

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::controller(BannerController::class)->group(function () {
        Route::prefix('banner')->group(function () {
            Route::get('index', 'index');
            Route::group(['middleware' => 'admin'], function () {
                Route::post('create', 'create');
                Route::post('update/{id}', 'update');
                Route::get('delete/{id}', 'delete');
                Route::get('edit/{id}', 'edit');
            });
        });
    });
});

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::prefix('category')->group(function () {
            Route::get('get-all', 'getAll');
            Route::group(['middleware' => 'admin'], function () {
                Route::get('index', 'index');
                Route::post('create', 'create');
                Route::post('update/{id}', 'update');
                Route::get('delete/{id}', 'delete');
                Route::get('edit/{id}', 'edit');
            });
        });
    });
});

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::controller(ProductController::class)->group(function () {
        Route::prefix('product')->group(function () {
            Route::get('product-by-category/{id}', 'productByCategory');
            Route::get('edit/{id}', 'edit');
            Route::get('list-favorite', 'listFavorite');

            Route::group(['middleware' => 'admin'], function () {
                Route::get('index', 'index');
                Route::post('create', 'create');
                Route::post('update/{id}', 'update');
                Route::get('delete/{id}', 'delete');
                Route::post('cart', 'cart');
            });
        });
    });
});

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::controller(PaymentController::class)->group(function () {
        Route::prefix('payment')->group(function () {
            Route::post('create-payment', 'createPayment');
        });
    });
});

Route::group(['prefix' => 'v1'], function () {
    Route::controller(PaymentController::class)->group(function () {
        Route::prefix('payment')->group(function () {
            Route::get('callback', 'callBack');
        });
    });
});


Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::group(['middleware' => 'admin'], function () {
                Route::get('top-product', 'topProductByMonth');
            });
        });
    });
});
