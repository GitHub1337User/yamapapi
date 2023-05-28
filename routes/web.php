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
    return view('pages.main');
})->name('main');



Route::middleware('auth')->group(function(){
    Route::post('/logout', [\App\Http\Controllers\AuthController::class,'perform'])->name('logout');
    Route::post('/add_point',[\App\Http\Controllers\PointController::class,'add_point'])->name('add_point');
    Route::get('/map', [\App\Http\Controllers\PointController::class,'map'])->name('map');
    Route::get('/get_points', [\App\Http\Controllers\PointController::class,'get_points'])->name('get_points');
    Route::delete('/delete_point/{id}', [\App\Http\Controllers\PointController::class,'delete_point'])->name('delete_point');
    Route::patch('/edit_point/{id}', [\App\Http\Controllers\PointController::class,'edit_point'])->name('edit_point');
});
Route::middleware('guest')->group(function(){
    Route::post('/login', [\App\Http\Controllers\AuthController::class,'login'])->name('login');
    Route::post('/register', [\App\Http\Controllers\AuthController::class,'register'])->name('register');
    Route::get('/login', [\App\Http\Controllers\AuthController::class,'loginIndex'])->name('loginIndex');
    Route::get('/register', [\App\Http\Controllers\AuthController::class,'regIndex'])->name('regIndex');
});
