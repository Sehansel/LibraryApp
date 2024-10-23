<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return redirect()->route('register');
});

Route::get('/dashboard', [BookController::class, 'dashboard'])->middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-photo', [ProfileController::class, 'pictureUpdate'])->name('photo.update');
    Route::get('/profile/{user}', [ProfileController::class, 'showProfilePhoto'])->name('profile.photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('admin/dashboard', [HomeController::class, 'index']) -> middleware(['auth', 'admin', 'prevent-back-history', 'redirect-if-logged-out'])->name('adminDashboard');

Route::get('/book/add', [BookController::class, 'addBook'])->middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out'])->name('addBook');
Route::get('/book/{id}', [BookController::class, 'book'])->middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out'])->name('bookDetail');
Route::post('/book/store', [BookController::class, 'storeBook'])->middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out'])->name('storeBook');
Route::post('/book/buy', [BookController::class, 'buyBook'])->middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out'])->name('buyBook');
