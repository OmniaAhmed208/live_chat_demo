<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatAdminController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('home', HomeController::class);

Route::resource('chat', ChatController::class);
Route::post('/save-data', [ChatController::class, 'saveData'])->name('saveData');

Route::get('/getChat', [ChatController::class, 'getChat'])->name('getChat');

Route::get('/image/{id}', [ChatController::class, 'getImage'])->name('image');

Route::group(['middleware' => 'admin'], function () 
{
    Route::resource('admin', AdminController::class);
    
    Route::get('chat', [ChatAdminController::class, 'chat'])->name('admin.chat');
    Route::get('/viewChat/{id}', [ChatAdminController::class, 'viewChat'])->name('viewChat');

    Route::post('/storeChat/{id}', [ChatAdminController::class, 'storeChat'])->name('storeChat');
    Route::get('/getChat/{id}', [ChatAdminController::class, 'getChat'])->name('getChatAdmin');

    Route::get('/fetchNewMessages', [ChatAdminController::class, 'fetchNewMessages'])->name('fetchNewMessages');
});

