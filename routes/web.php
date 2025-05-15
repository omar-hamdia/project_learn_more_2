<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stages\StageController;
use App\Models\Stage;
use App\Models\User;
Route::get('/', function () {
    return view('welcome');
});
// learnschool
// name: dash.grade.index
Route::prefix('learnschool/')->group(function () {
Route::prefix('dashboard/')->name('dash.')->group(function () {
    Route::prefix('grades')->controller(StageController::class)->name('grade.')->group(function () {
       Route::get('/', 'index')->name('index');
       Route::get('/create', 'create')->name('create');
       Route::post('/add', 'add')->name('add');
       Route::get('/getdata', 'getdata')->name('getdata');
       Route::get('/getactive', 'getactive')->name('getactive');

    });
});
});
































Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
