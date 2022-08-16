<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\OfficerController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', [CompanyController::class, 'index']);


Route::get('/about/{id?}', [CompanyController::class, 'about']);

Route::apiResource('/product',ProductController::class);
Route::apiResource('/department',DepartmentController::class);
Route::apiResource('/officer',OfficerController::class);

// ค้นหาชื่อแผนก
Route::get('/search/department', [DepartmentController::class, 'search']);


Route::post('/auth/register',[AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');

//get user profile
Route::get('/auth/me',[AuthController::class, 'me'])->middleware('auth:sanctum');
