<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController as AuthV1Controller;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\UserController;


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

Route::post('register', [AuthV1Controller::class, 'register']);
Route::post('verifyotp', [AuthV1Controller::class, 'verifyotp']);
Route::post('/login', [AuthV1Controller::class, 'login']);
Route::get( '/some_url', function () {
	 $resp['status'] = 'UA';
	 $resp['message'] = 'invalid token'; 
     return response()->json($resp);
    }
)->name('login');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

 Route::post('update-profile', [UserController::class, 'updateProfile']);

});


Route::post('home', [HomeController::class, 'index']);

