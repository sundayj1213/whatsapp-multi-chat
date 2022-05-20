<?php

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    // return Inertia::render('Dashboard');
    return view('index');
})->name('dashboard');

Route::get('/', function () {
    return Inertia::render('Home', [
        'users' => User::get(),
    ]);
})->name('home');

Route::get('chat/{id}', function (Request $request) {
    return Inertia::render('Chat', [
        'config' => config('socketio'),
        'chats' => $request->chats
    ]);
})->name('chat');

Route::get('dash/{id}', function () {
    return Inertia::render('Dash', [
        'config' => config('socketio')
    ]);
})->name('dash');