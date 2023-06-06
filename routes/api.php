<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\PurseController;

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

    Route::get("/fetchPurseHistory", [PurseController::class, "fetchPurseHistory"]);
    Route::get("/fetchPurseHistory", [PurseController::class, "fetchPurseHistory"]);
    Route::get("/fetchPursesOfUser", [PurseController::class, "fetchPursesOfUser"]);
    Route::post("/createPurse", [PurseController::class, "createPurse"]);
    Route::post("/addAmountToPurse", [PurseController::class, "addAmountToPurse"]);
    Route::post("/exchangePurseToPurse", [PurseController::class, "exchangePurseToPurse"]);
    // Route::patch("/changeCurrencyWherePurse", [PurseController::class, "changeCurrencyWherePurse"]);

    Route::post("/logout", [AuthController::class, "logout"]);
});


Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
