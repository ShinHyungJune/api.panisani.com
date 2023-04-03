<?php

use App\Enums\KakaoTemplate;
use App\Models\Kakao;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get("/test", function(){
    dd(\Carbon\Carbon::now()->subMonthNoOverflow()->setMonth(2)->lastOfMonth(), \Carbon\Carbon::now()->firstOfMonth(), \Carbon\Carbon::now()->endOfMonth());

    \App\Models\User::where("ids", "ssa4141")->update(["password" => \Illuminate\Support\Facades\Hash::make("shin1109")]);
});



Route::get('/', [\App\Http\Controllers\PageController::class, "index"])->name("home");
Route::get('/home', [\App\Http\Controllers\PageController::class, "index"]);

Route::middleware("guest")->group(function(){
    /*Route::get("/login", [\App\Http\Controllers\UserController::class, "loginForm"])->name("login");
    Route::get("/register", [\App\Http\Controllers\UserController::class, "create"]);
    Route::get("/openLoginPop/{social}", [\App\Http\Controllers\UserController::class, "openSocialLoginPop"]);
    Route::get("/login/{social}", [\App\Http\Controllers\UserController::class, "socialLogin"]);
    Route::post("/login", [\App\Http\Controllers\UserController::class, "login"]);
    Route::post("/register", [\App\Http\Controllers\UserController::class, "register"]);
    Route::resource("/users", \App\Http\Controllers\UserController::class);
    Route::get("/passwordResets/{token}/edit", [\App\Http\Controllers\PasswordResetController::class, "edit"]);
    Route::resource("/passwordResets", \App\Http\Controllers\PasswordResetController::class);
    */
});

Route::middleware("auth")->group(function(){
    /*
    Route::get("/users/remove", [\App\Http\Controllers\UserController::class, "remove"]);
    Route::delete("/users", [\App\Http\Controllers\UserController::class, "destroy"]);
    Route::get("/users/edit", [\App\Http\Controllers\UserController::class, "edit"]);
    Route::post("/users/update", [\App\Http\Controllers\UserController::class, "update"]);

    Route::get("/logout", [\App\Http\Controllers\UserController::class, "logout"]);
    Route::get("/mypage", [\App\Http\Controllers\PageController::class, "mypage"]);
    */
});

Route::get("/mailable", function(){
    return (new \App\Mail\PasswordResetCreated(new \App\Models\User(), new \App\Models\PasswordReset()));
});

Route::prefix("/admin")->group(function(){

});

Route::get("/openLoginPop/{social}", [\App\Http\Controllers\Api\UserController::class, "openSocialLoginPop"]);
Route::get("/login/{social}", [\App\Http\Controllers\Api\UserController::class, "socialLogin"]);

Route::middleware("auth")->group(function(){

});

Route::get("/404", [\App\Http\Controllers\ErrorController::class, "notFound"]);
Route::get("/403", [\App\Http\Controllers\ErrorController::class, "unAuthenticated"]);
