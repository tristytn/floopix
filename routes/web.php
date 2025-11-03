<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HotController;
use App\Http\Controllers\FriendController;


Route::get('/', function () {
    return redirect()->route('hot');
});
// -------------------- AUTH --------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// -------------------- SEARCH --------------------
Route::get('/search', [UserController::class, 'search'])->name('search');

// -------------------- PROTECTED ROUTES --------------------
Route::middleware('auth')->group(function () {

    // Hot page (main feed)
    Route::get('/hot', [HotController::class, 'index'])->name('hot');

    // VRIENDEN PAGINA
    Route::get('/friends-posts', [PostController::class, 'friendsPosts'])
    ->middleware('auth')
    ->name('posts.friends');


    // Posts
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{id}/dislike', [PostController::class, 'dislike'])->name('posts.dislike');
    Route::post('/posts/{id}/comment', [PostController::class, 'comment'])->name('posts.comment');

    // User profiles
    Route::get('/user/{id}', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->name('user.profile'); // <-- Add this

    Route::post('/user/{id}/add-friend', [ProfileController::class, 'addFriend'])->name('add.friend');
    Route::post('/friend/remove/{id}', [ProfileController::class, 'removeFriend'])->name('remove.friend');
    Route::post('/profile/{id}/delete-friend', [ProfileController::class, 'deleteFriend'])->name('delete.friend');



Route::middleware(['auth'])->group(function () {
    Route::post('/friends/request/{id}', [FriendController::class, 'sendRequest'])->name('friends.request');
    Route::post('/friends/accept/{id}', [FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/decline/{id}', [FriendController::class, 'declineRequest'])->name('friends.decline');
    Route::get('/friends/requests', [FriendController::class, 'pendingRequests'])->name('friends.requests');
});

});
