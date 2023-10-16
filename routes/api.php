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
Route::post('/visits', [\App\Http\Controllers\VisitController::class, 'store']);

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

////// Category
Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index']);
Route::get('/is_Visible', [\App\Http\Controllers\CategoryController::class, 'isVisible']);
Route::get('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'show']);
Route::get('/categoriesSome/{categoryID}', [\App\Http\Controllers\CategoryController::class, 'showsome']);
Route::get('/numberAll', [\App\Http\Controllers\CategoryController::class, 'numberAll']);

///// SubCategory
Route::get('/subCategories', [\App\Http\Controllers\SubCategoryController::class, 'index']);
Route::get('/sub_is_Visible', [\App\Http\Controllers\SubCategoryController::class, 'isVisible']);
Route::get('/subCategories/{subcategory}', [\App\Http\Controllers\SubCategoryController::class, 'show']);

/////  Product
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
Route::get('/isVisible', [\App\Http\Controllers\ProductController::class, 'isVisible']);
Route::get('/orderByPosition', [\App\Http\Controllers\ProductController::class, 'orderByPosition']);
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show']);

////// Feature
Route::get('/features', [\App\Http\Controllers\FeatureController::class, 'index']);
Route::get('/category/{categoryName}', [\App\Http\Controllers\FeatureController::class, 'getFeatureByCategory']);

///// Public Feature
Route::get('/publicfeatures', [\App\Http\Controllers\PublicFeatureController::class, 'index']);


Route::group(['middleware' => 'jwt.auth'], function () {

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
   // Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store']);
    Route::post('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy']);
    Route::get('/switchCategory/{category}', [\App\Http\Controllers\CategoryController::class, 'switchCategory']);

    ///// SubCategory
    Route::post('/subCategories', [\App\Http\Controllers\SubCategoryController::class, 'store']);
    Route::post('/subCategories/{subcategory}', [\App\Http\Controllers\SubCategoryController::class, 'update']);
    Route::delete('/subCategories/{subcategory}', [\App\Http\Controllers\SubCategoryController::class, 'destroy']);
    Route::get('/subSwitchCategory/{subcategory}', [\App\Http\Controllers\SubCategoryController::class, 'switchSubCategory']);


    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy']);
    Route::get('/switchProduct/{product}', [\App\Http\Controllers\ProductController::class, 'switchProduct']);
//    Route::get('/category/{categoryName}/products', [\App\Http\Controllers\ProductController::class, 'getProducts']);
    Route::get('/category/{subcategoryName}/products', [\App\Http\Controllers\ProductController::class, 'getProduct']);


    Route::post('/get', [\App\Http\Controllers\VisitController::class, 'get']);


////// Feature

    Route::post('/features', [\App\Http\Controllers\FeatureController::class, 'store']);
    Route::delete('/features/{feature}', [\App\Http\Controllers\FeatureController::class, 'destroy']);


    ///// public Feature
    Route::post('/publicfeatures', [\App\Http\Controllers\PublicFeatureController::class, 'store']);
    Route::delete('/publicfeatures/{feature}', [\App\Http\Controllers\PublicFeatureController::class, 'destroy']);

});
