<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\Helpers;
use App\Http\Controllers\ForbesTopController;
use App\Http\Controllers\AuthController;

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

Route::group([
    'prefix'     => 'user',
    'middleware' => 'userauth',
], function () {
    Route::get('dashboard', [ForbesTopController::class, 'getMostCounts']);

    /// Table Records Functionality Routes
    Route::get('csv-records', [ForbesTopController::class, 'getRecipients']);
    Route::post('csv-search', [ForbesTopController::class, 'findRecipients']);

    /// CSV Functionality Routes
    Route::get('csv-upload', function () {
        return view('csvuploader');
    });
    Route::post('csv-upload', [ForbesTopController::class, 'uploadCSVContent']);

    Route::get('message', function () {
        return view('message');
    });
});

/// User Auth Functionality Routes
Route::get('/login', function () {
    return view('login');
})->middleware('userauth');
Route::post('/login', [AuthController::class, 'userLogin'])->middleware('userauth');
Route::get('/logout', [AuthController::class, 'userLogout'])->middleware('userauth');
