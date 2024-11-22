<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Profile\ProfileController;
use App\Http\Controllers\V1\Profile\ProfileMessageController;
use App\Http\Controllers\V1\Profile\UserProfilePairsController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::post('/', [ProfileController::class, 'create']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('/photo', [ProfileController::class, 'uploadPhoto']);
        Route::delete('photos/{id}', [ProfileController::class, 'deletePhoto']);
        Route::post('/search', [ProfileController::class, 'searchProfiles']);
        Route::get('/pairs', [UserProfilePairsController::class, 'list']);;

        Route::get('/{profileUserId}', [ProfileController::class, 'show']);
        Route::post('{profileUserId}/reaction', [ProfileController::class, 'setReaction']);
        Route::get('liked/count', [ProfileController::class, 'getCountLikedProfiles']);});

    Route::prefix('profile_messages')->group(function () {
        Route::post('/', [ProfileMessageController::class, 'send']);
        Route::get('/', [ProfileMessageController::class, 'getNewMessagesByDate']);
        Route::get('history', [ProfileMessageController::class, 'getMessagesHistory']);
    });
});
