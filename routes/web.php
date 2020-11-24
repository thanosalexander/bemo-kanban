<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('columns', \App\Http\Controllers\ColumnController::class)->middleware('auth:web');

Route::get('columns', [\App\Http\Controllers\ColumnController::class,'index'])->name('columns.index')->middleware('auth:web');
Route::post('columns', [\App\Http\Controllers\ColumnController::class,'store'])->name('columns.store')->middleware('auth:web');
Route::post('columns/{column}/sort', [\App\Http\Controllers\ColumnController::class,'sort'])->name('columns.sort')->middleware('auth:web');
Route::delete('columns/{column}', [\App\Http\Controllers\ColumnController::class,'delete'])->name('columns.delete')->middleware('auth:web');

Route::get('{column}/cards', [\App\Http\Controllers\CardController::class,'index'])->name('cards.index')->middleware('auth:web');
Route::post('{column}/cards', [\App\Http\Controllers\CardController::class,'store'])->name('cards.store')->middleware('auth:web');
Route::put('cards/{card}', [\App\Http\Controllers\CardController::class,'update'])->name('cards.update')->middleware('auth:web');
Route::delete('cards/{card}', [\App\Http\Controllers\CardController::class,'delete'])->name('cards.delete')->middleware('auth:web');

Route::post('db-download', [\App\Http\Controllers\DBController::class,'download'])->name('db.download')->middleware('auth:web');

