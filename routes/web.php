<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/', [UserController::class, 'index'])->name('user.index');
Route::post('/user', [UserController::class, 'registerUser'])->name('user.register');
Route::get('/user/login', [UserController::class, 'showLogin'])->name('user.showLogin');
Route::post('/user/authenticate', [UserController::class, 'loginUser'])->name('user.login');
// LOG USER OUT ROUTE
Route::post('/logout', [UserController::class, 'logoutUser'])->name('logout');

// GOES TO GITHUB
Route::get('/sign-in/github', [UserController::class, 'githubSignIn']);
// RETURNS BACK TO APP FROM GITHUB
Route::get('/sign-in/github/redirect', [UserController::class, 'githubRedirect']);
// GOES TO SPOTIFY
Route::get('/sign-in/spotify', [UserController::class, 'spotifySignIn']);
// RETURNS BACK TO APP FROM SPOTIFY
Route::get('/sign-in/spotify/redirect', [UserController::class, 'spotifyRedirect']);