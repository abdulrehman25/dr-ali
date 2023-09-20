<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stripe1',[StripeController::class, 'pay']);
Route::get('/emailer',[StripeController::class, 'emailer']);
Route::post('/pay',[StripeController::class, 'makePayment']);

Route::post('stripe',[StripeController::class,'stripePost'])->name('stripe.post');

Route::get('/checkout', [StripeController::class, 'checkout'])->name('checkout');
Route::get('/success', [StripeController::class, 'success'])->name('checkout.success');
Route::get('/cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');
Route::post('/webhook', [StripeController::class, 'webhook'])->name('checkout.webhook');