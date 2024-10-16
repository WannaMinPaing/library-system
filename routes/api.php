<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('logout', [AuthController::class, 'logout']);

    //Book
    Route::controller(BookController::class)->prefix('/book')->group(function () {
        Route::post('/', 'index');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/delete', 'delete');
        Route::post('/detail', 'detail');
    });

    //User
    Route::controller(UserController::class)->prefix('/user')->group(function () {
        Route::post('/', 'index');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/detail', 'detail');
    });

    //Loan
    Route::controller(LoanController::class)->prefix('/loan')->group(function () {
        Route::post('/', 'index');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
    });

});
