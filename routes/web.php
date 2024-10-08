<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect()->route('register');
});

Route::get('/dashboard', function () {
    if(Auth::check()){
        return view('dashboard');
    }else{
        return view('/');
    }
    
})->middleware(['auth', 'verified', 'prevent-back-history', 'redirect-if-logged-out'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-photo', [ProfileController::class, 'pictureUpdate'])->name('photo.update');
    Route::get('/profile/{user}', [ProfileController::class, 'showProfilePhoto'])->name('profile.photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('admin/dashboard', [HomeController::class, 'index']) -> middleware(['auth', 'admin', 'prevent-back-history']);
