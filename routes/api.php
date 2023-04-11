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
Route::post("/visits", [\App\Http\Controllers\Api\VisitController::class, "store"]);
Route::post("/login", [\App\Http\Controllers\Api\UserController::class, "login"]);
Route::post("/users", [\App\Http\Controllers\Api\UserController::class, "store"]);

Route::post("/findIds", [\App\Http\Controllers\Api\FindIdController::class, "store"]);
Route::post("/findPasswords", [\App\Http\Controllers\Api\FindPasswordController::class, "store"]);
Route::patch("/findPasswords", [\App\Http\Controllers\Api\FindPasswordController::class, "update"]);

Route::post("/verifyNumbers", [\App\Http\Controllers\Api\VerifyNumberController::class, "store"]);
Route::patch("/verifyNumbers", [\App\Http\Controllers\Api\VerifyNumberController::class, "update"]);

Route::resource("/youtubes", \App\Http\Controllers\Api\YoutubeController::class);

Route::get("/communitiesByChar", [\App\Http\Controllers\Api\CommunityController::class, "indexByChar"]);
Route::get("/communities", [\App\Http\Controllers\Api\CommunityController::class, "index"]);
Route::get("/communities/{community}", [\App\Http\Controllers\Api\CommunityController::class, "show"]);
Route::get("/boards", [\App\Http\Controllers\Api\BoardController::class, "index"]);

Route::get("/posts", [\App\Http\Controllers\Api\PostController::class, "index"]);
Route::get("/posts/{post}", [\App\Http\Controllers\Api\PostController::class, "show"]);
Route::get("/comments", [\App\Http\Controllers\Api\CommentController::class, "index"]);
Route::get("/commentsByBest", [\App\Http\Controllers\Api\CommentController::class, "indexByBest"]);
Route::get("/specials", [\App\Http\Controllers\Api\SpecialController::class, "index"]);
Route::get("/searchRankings", [\App\Http\Controllers\Api\SearchRankingController::class, "index"]);
Route::get("/recommendUsers", [\App\Http\Controllers\Api\RecommendUserController::class, "index"]);
Route::get("/games", [\App\Http\Controllers\Api\GameController::class, "index"]);
Route::get("/subscriptions", [\App\Http\Controllers\Api\SubscriptionController::class, "index"]);
Route::get("/users/{user}", [\App\Http\Controllers\Api\UserController::class, "show"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("/user",function (Request $request) {
        return $request->user() ? \App\Http\Resources\UserResource::make($request->user()) : "";
    });

    Route::post("/logout", [\App\Http\Controllers\Api\UserController::class, "logout"]);
    Route::patch("/users", [\App\Http\Controllers\Api\UserController::class, "update"]);
    Route::delete("/users", [\App\Http\Controllers\Api\UserController::class, "destroy"]);
    Route::get("/logout", [\App\Http\Controllers\Api\UserController::class, "logout"]);

    Route::resource("/communities", \App\Http\Controllers\Api\CommunityController::class)->except(["index", "show"]);
    Route::get("/boardsByMe", [\App\Http\Controllers\Api\BoardController::class, "indexByMe"]);
    Route::patch("/boards/up", [\App\Http\Controllers\Api\BoardController::class, "up"]);
    Route::patch("/boards/down", [\App\Http\Controllers\Api\BoardController::class, "down"]);
    Route::resource("/boards", \App\Http\Controllers\Api\BoardController::class)->except(["index"]);

    Route::resource("/posts", \App\Http\Controllers\Api\PostController::class)->except(["index", "show"]);
    Route::resource("/tempPosts", \App\Http\Controllers\Api\TempPostController::class);
    Route::resource("/comments", \App\Http\Controllers\Api\CommentController::class)->except(["index"]);
    Route::resource("/likes", \App\Http\Controllers\Api\LikeController::class);
    Route::resource("/hates", \App\Http\Controllers\Api\HateController::class);
    Route::resource("/reports", \App\Http\Controllers\Api\ReportController::class);
    Route::post("/subscriptions", [\App\Http\Controllers\Api\SubscriptionController::class, "store"]);
});

