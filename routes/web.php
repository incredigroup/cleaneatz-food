<?php

use App\Http\Controllers\CafeMenuController;
use App\Http\Controllers\OnlineOrderController;
use App\Http\Controllers\PaymentJsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

Route::get('/mealplanmenu', fn() => redirect('/meal-plans'));

 
Route::get('/cafe-menu/{categorySlug?}', [CafeMenuController::class, 'index'])->name('cafe-menu');

Route::get('meal-plans', [OnlineOrderController::class, 'mealPlanMenu'])->name(
    'site.meal-plan-menu',
);
// ------------------------
Route::get('order/thank-you', [OnlineOrderController::class, 'thankyou'])->name(
    'site.thank-you-order',
);
// ----------------
Route::get('c', [OnlineOrderController::class, 'index'])->name('site.order');

Route::get('order/{order}/complete', [OnlineOrderController::class, 'orderComplete'])->name(
    'site.order-complete',
);

Route::post('order', [OnlineOrderController::class, 'save'])->name('site.order.save');

Route::post('order/cart', [OnlineOrderController::class, 'addToCart'])->name(
    'site.shopping-cart.add',
);

Route::get('order/cart', [OnlineOrderController::class, 'showShoppingCart'])->name(
    'site.shopping-cart',
);

Route::get('order/cart/login', [OnlineOrderController::class, 'login'])->name(
    'site.shopping-cart.login',
);

Route::post('order/cart/coupon', [OnlineOrderController::class, 'addCouponToCart'])->name(
    'site.shopping-cart.coupon',
);

Route::delete('order/cart/coupon', [OnlineOrderController::class, 'removeCouponFromCart'])->name(
    'site.shopping-cart.removeCoupon',
);

Route::get('custom', [OnlineOrderController::class, 'customMenu'])->name('site.custom-menu');

Route::post('order/custom-cart', [OnlineOrderController::class, 'addCustomMealsToCart'])->name(
    'site.shopping-cart.add-custom',
);

Auth::routes();

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile', [ProfileController::class, 'postIndex']);

Route::get('/profile/billing', [ProfileController::class, 'billing'])->name('profile.billing');
Route::post('/profile/billing', [ProfileController::class, 'postBilling']);

Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
Route::post('/profile/password', [ProfileController::class, 'postPassword']);

Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');

Route::post('/paymentjs/authorize-session/{storeLocationId}', [
    PaymentJsController::class,
    'authorizeSession',
]);
Route::post('/webhook/paymentjs', [PaymentJsController::class, 'tokenizeWebhook']);
