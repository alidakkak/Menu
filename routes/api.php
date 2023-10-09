<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
   // Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index']);
    Route::get('/is_Visible', [\App\Http\Controllers\CategoryController::class, 'isVisible']);
    Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store']);
    Route::post('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update']);
    Route::get('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'show']);
    Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy']);
    Route::get('/switchCategory/{category}', [\App\Http\Controllers\CategoryController::class, 'switchCategory']);


    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/isVisible', [\App\Http\Controllers\ProductController::class, 'isVisible']);
    Route::get('/orderByPosition', [\App\Http\Controllers\ProductController::class, 'orderByPosition']);
    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update']);
    Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show']);
    Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy']);
    Route::get('/switchProduct/{product}', [\App\Http\Controllers\ProductController::class, 'switchProduct']);
    Route::get('/category/{categoryName}/products', [\App\Http\Controllers\ProductController::class, 'getProducts']);


    Route::post('/visits', [\App\Http\Controllers\VisitController::class, 'store']);
    Route::post('/get', [\App\Http\Controllers\VisitController::class, 'get']);


////// Feature


    Route::get('/features', [\App\Http\Controllers\FeatureController::class, 'index']);
    Route::post('/features', [\App\Http\Controllers\FeatureController::class, 'store']);
    Route::delete('/features/{feature}', [\App\Http\Controllers\FeatureController::class, 'destroy']);

});
