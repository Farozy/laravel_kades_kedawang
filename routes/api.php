<?php

use App\Http\Controllers\Api\Kades\ClientApiController;
use App\Http\Controllers\Api\Kades\ContactApiController;
use App\Http\Controllers\Api\Kades\TokenApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;

//Pelopor
Route::prefix("kedawang")->group(static function() {

    // Contact
    Route::resource("contact", ContactApiController::class);
    // Client
    Route::resource("client", ClientApiController::class);
    Route::get("client/token", [ClientApiController::class, "showToken"]);
    Route::post("client/token", [ClientApiController::class, "token"]);
    // Token
    Route::resource("token", TokenApiController::class);
});

Route::prefix("auth")->middleware(["auth:sanctum"])->group(static function () {
    Route::post('register', [AuthApiController::class, "register"])->withoutMiddleware("auth:sanctum");
    Route::post('login', [AuthApiController::class, "login"])->withoutMiddleware("auth:sanctum");
    Route::post('logout', [AuthApiController::class, "logout"]);
    Route::post('refresh-token', [AuthApiController::class, "refresh"]);
    Route::post('me', [AuthApiController::class, "me"]);
});
