<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\OtherFavoriteController;
use App\Http\Controllers\OtherFolderController;
use App\Models\LandingPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/favorites/index', [LandingPageController::class, 'favorites_index'])->name('favorites.index');

    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::post('/favorites/add_to_folder', [FavoriteController::class, 'addToFolder'])->name('favorites.add_to_folder');
    Route::put('/favorites/update', [FavoriteController::class, 'update'])->name('favorites.update');
    Route::delete('/favorites/destroy', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/folders/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit');
    Route::post('/folders/update', [FolderController::class, 'update'])->name('folders.update');
    Route::post('/folders/destroy', [FolderController::class, 'destroy'])->name('folders.destroy');

    Route::get('/others/favorites', [OtherFavoriteController::class, 'index'])->name('other_favorites.index');
    Route::post('/others/favorites/store', [OtherFavoriteController::class, 'store'])->name('other_favorites.store');
    Route::post('/others/favorites/update', [OtherFavoriteController::class, 'update'])->name('other_favorites.update');
    Route::post('/others/favorites/destroy', [OtherFavoriteController::class, 'destroy'])->name('other_favorites.destroy');
    Route::post('/others/folders/update', [OtherFolderController::class,'update'])->name('other_folders.update');
    Route::post('/others/folders/destroy', [OtherFolderController::class,'destroy'])->name('other_folders.destroy');

    Route::get('/', [LandingPageController::class, 'index'])->name('landing_pages.index');
Route::get('/show/{id}', [LandingPageController::class, 'show'])->name('landing_pages.show');
Route::get('/sp/show/{id}', [LandingPageController::class, 'sp_show'])->name('landing_pages.sp.show');

Route::get('/others/{id}', [LandingPageController::class, 'others_show'])->name('others.show');
});



require __DIR__.'/auth.php';