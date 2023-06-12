<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\WalletController;

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

Route::middleware('auth:api')->group(function () {
    // Route::apiResource("exchange", ExchangeController::class);

    Route::get("/fetchWalletExchange", [WalletController::class, "fetchWalletExchange"]);
    Route::get("/fetchWalletHistory", [WalletController::class, "fetchWalletHistory"]);
    Route::get("/fetchWalletsOfUser", [WalletController::class, "fetchWalletsOfUser"]);
    Route::post("/createWallet", [WalletController::class, "createWallet"]);
    Route::post("/addAmountToWallet", [WalletController::class, "addAmountToWallet"]);
    Route::post("/exchangeWalletToWallet", [WalletController::class, "exchangeWalletToWallet"]);
    // Route::patch("/changeCurrencyWherePurse", [WalletController::class, "changeCurrencyWherePurse"]);

    Route::post("/logout", [AuthController::class, "logout"]);
});


Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
