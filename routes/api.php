<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 
Route::get('/error',function(){
return response()->json(['status'=>false,'msg'=>'Invalid User']);
})->name('user.login');

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::group(['middleware'=>'auth:api'],function(){
Route::get('profile',[AuthController::class,'myprofile']);
Route::post('addtask',[AuthController::class,'addtask']);
Route::get('gettask',[AuthController::class,'gettask']);
Route::delete('deletetask/{id}',[AuthController::class,'deletetask']);
});
