<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoanProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicLoanController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserLoanController;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/paystack/verify', [PaymentController::class, 'verify']);


Route::prefix('public')->group(function () {
    Route::get('loan-products', [PublicLoanController::class, 'index']);
    Route::get('loan-products/{id}', [PublicLoanController::class, 'view']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/paystack/initialize', [PaymentController::class, 'initialize']);

    //Admin route

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/loan-products', [AdminLoanProductController::class, 'index']);
            Route::post('/loan-products', [AdminLoanProductController::class, 'store']);
            Route::get('/loan-products/{id}', [AdminLoanProductController::class, 'view']);
            Route::post('/loan-products/{id}', [AdminLoanProductController::class, 'update']);

            Route::get('/loans', [AdminLoanProductController::class, 'AllUserLoan']);
            Route::get('/loans/{id}', [AdminLoanProductController::class, 'ViewSingleUserLoan']);
            Route::post('/loans/{id}/approve', [AdminLoanProductController::class, 'ApproveLoan']);
            Route::post('/loans/{id}/reject', [AdminLoanProductController::class, 'RejectLoan']);
            Route::get('/dashboard-stats', [AdminDashboardController::class, 'Stats']);
        });
    });

    Route::prefix('user')->group(function () {
        Route::post('/loans', [UserLoanController::class, 'LoanApplication']);
        Route::get('/loans', [UserLoanController::class, 'AllLoans']);
        Route::get('/loans/{id}', [UserLoanController::class, 'viewLoan']);
        Route::get('/dashboard-stats', [UserDashboardController::class, 'Stats']);;
    });
});
