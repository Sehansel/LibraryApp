<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PublisherController;

Route::get('/', function () {
    return redirect()->route('register');
});

Route::middleware(['auth', 'verified', 'check-session'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-photo', [ProfileController::class, 'pictureUpdate'])->name('photo.update');
    Route::get('/profile/{user}', [ProfileController::class, 'showProfilePhoto'])->name('profile.photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out','check-session'])->group(function(){
    Route::get('/dashboard', [BookController::class, 'dashboard'])->name('dashboard');
    Route::get('/book/{id}', [BookController::class, 'book'])->name('bookDetail');
    Route::post('/buy/book', [BookController::class, 'buyBook'])->name('buyBook');
    Route::get('/books', [BookController::class, 'ownBook'])->name('ownBook');
    Route::get('/books/{id}', [BookController::class, 'userBook'])->name('userBookDetail');
    Route::get('/publisher', [PublisherController::class, 'allPublisher'])->name('allPublisher');
    Route::get('/publisher/{id}', [PublisherController::class, 'detailPublisher'])->name('publisherDetail');
});

Route::get('admin/dashboard', [HomeController::class, 'index']) -> middleware(['auth', 'admin', 'prevent-back-history', 'redirect-if-logged-out','check-session'])->name('adminDashboard');

Route::middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out','check-session', 'admin'])->group(function(){
    Route::get('/add/book', [BookController::class, 'addBook'])->name('addBook');
    Route::post('/store/book', [BookController::class, 'storeBook'])->name('storeBook');
    Route::get('/edit/book/{id}', [BookController::class, 'editBook'])->name('editBook');
    Route::patch('/update/book/{id}', [BookController::class, 'updateBook'])->name('updateBook');
    Route::delete('/delete/book/{id}', [BookController::class, 'deleteBook'])->name('deleteBook');
    Route::get('/add/publisher', [PublisherController::class, 'addPublisher'])->name('addPublisher');
    Route::post('/store/publisher', [PublisherController::class, 'storePublisher'])->name('storePublisher');
    Route::get('/edit/publisher/{id}', [PublisherController::class, 'editPublisher'])->name('editPublisher');
    Route::patch('/update/publisher/{id}', [PublisherController::class, 'updatePublisher'])->name('updatePublisher');
    Route::delete('/delete/publisher/{id}', [PublisherController::class, 'deletePublisher'])->name('deletePublisher');
});

