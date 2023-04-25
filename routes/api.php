<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookAppointmentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFeedbackController;
use App\Http\Controllers\UserReportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('get_user_verification_code', [AuthController::class, 'generateUserVerifyCode']);
Route::post('verify_user', [AuthController::class, 'verifyUserCode']);
Route::post('register',[AuthController::class,'register']);
Route::post('update_user', [AuthController::class, 'updateUserData']);
Route::post('login',[AuthController::class,'login']);


Route::post('book-appointment',[BookAppointmentController::class,'bookAppointment']);

Route::post('password/email',  ForgotPasswordController::class, '__invoke');
Route::post('password/code/check', CodeCheckController::class,'__invoke');
Route::post('password/reset', ResetPasswordController::class,'__invoke');
#update user Profile
Route::get('edit/{id}', [UserController::class,'getUser']);
Route::post('update/{id}', [UserController::class,'updateUserProfile']);

#users feedback 
Route::post('users_feeback', [UserFeedbackController::class, 'storeUserFeedback']);

#user reports
Route::post('user_reports', [UserReportController::class, 'storeUserReport']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('getusers',[AuthController::class,'getUser']);
    //Route::get('user',[AuthController::class,'user']);

});

//Created for testing
Route::get('test_mail','App\Http\Controllers\ForgotPasswordController@testMail');