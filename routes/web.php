<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\FotoController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
 
Route::get('/',  [Dashboard::class, 'guest'])->name('guest');
 
Route::get('/dashboard',  [Dashboard::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard', [Dashboard::class, 'commentStore'])->middleware(['auth', 'verified'])->name('comment.store');
Route::post('/like', [Dashboard::class, 'like'])->middleware(['auth', 'verified'])->name('like'); 

Route::get('/albums',  [AlbumController::class, 'index'])->middleware(['auth', 'verified'])->name('albums');
Route::post('/albums', [AlbumController::class, 'store'])->middleware(['auth', 'verified'])->name('album.store');
Route::put('/albums/{album}', [AlbumController::class, 'update'])->middleware(['auth', 'verified'])->name('album.update');
Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->middleware(['auth', 'verified'])->name('album.destroy');


Route::get('/fotos',  [FotoController::class, 'index'])->middleware(['auth', 'verified'])->name('fotos');
Route::post('/fotos', [FotoController::class, 'store'])->middleware(['auth', 'verified'])->name('foto.store');
Route::put('/fotos/{foto}', [FotoController::class, 'update'])->middleware(['auth', 'verified'])->name('foto.update');
Route::delete('/fotos/{foto}', [FotoController::class, 'destroy'])->middleware(['auth', 'verified'])->name('foto.destroy');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
