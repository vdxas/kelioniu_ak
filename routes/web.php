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
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/{tz}', [App\Http\Controllers\HomeController::class, 'index']);


Route::get('/get_airports', [App\Http\Controllers\AirportController::class, 'index']);


Route::get('/get_timezones', [App\Http\Controllers\FlightController::class, 'timezones']);

Route::get('/get_flights/{tz}', [App\Http\Controllers\FlightController::class, 'index_tz']);
Route::get('/get_flights', [App\Http\Controllers\FlightController::class, 'index']);

Route::post('/store_flight', [App\Http\Controllers\FlightController::class, 'store']);
Route::post('/update_flight', [App\Http\Controllers\FlightController::class, 'update']);
