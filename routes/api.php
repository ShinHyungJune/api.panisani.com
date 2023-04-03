<?php

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::post("/imageUpload", [\App\Http\Controllers\Api\ImageController::class, "store"]);
Route::post("/visitors", [\App\Http\Controllers\Api\VisitorController::class, "store"]);
Route::post("/login", [\App\Http\Controllers\Api\UserController::class, "login"]);
Route::post("/users", [\App\Http\Controllers\Api\UserController::class, "store"]);

Route::post("/findIds", [\App\Http\Controllers\Api\FindIdController::class, "store"]);
Route::post("/findPasswords", [\App\Http\Controllers\Api\FindPasswordController::class, "store"]);
Route::patch("/findPasswords", [\App\Http\Controllers\Api\FindPasswordController::class, "update"]);

Route::post("/verifyNumbers", [\App\Http\Controllers\Api\VerifyNumberController::class, "store"]);
Route::patch("/verifyNumbers", [\App\Http\Controllers\Api\VerifyNumberController::class, "update"]);

Route::resource("/youtubes", \App\Http\Controllers\Api\YoutubeController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("/user",function (Request $request) {
        return $request->user() ? \App\Http\Resources\UserResource::make($request->user()) : "";
    });

    Route::patch("/users", [\App\Http\Controllers\Api\UserController::class, "update"]);
    Route::delete("/users", [\App\Http\Controllers\Api\UserController::class, "destroy"]);
    Route::get("/logout", [\App\Http\Controllers\Api\UserController::class, "logout"]);

});
