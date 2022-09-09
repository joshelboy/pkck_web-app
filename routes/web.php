<?php

use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\StravaController;
use Illuminate\Foundation\Application;
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

Route::get('/login', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/strava', function () {
    return Inertia::render('Strava');
})->name('strava');

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/bbq/all', function () {
    return Inertia::render('BBQ_Calendar');
})->name('bbq_all');

Route::middleware(['auth:sanctum', 'verified'])->get('/bbq/create', function () {
    return Inertia::render('BBQ_Calendar/CreateEvent');
})->name('bbq_create');

Route::middleware(['auth:sanctum', 'verified'])->get('/bbq/edit/{id}', function ($id) {
    return Inertia::render('BBQ_Calendar/EditEvent', [ 'id' => $id, ] );
})->name('bbq_edit');

Route::get('/auth/discord/redirect', [DiscordController::class, 'handleDiscordRedirect']);
Route::get('/auth/discord/callback', [DiscordController::class, 'handleDiscordCallback']);

Route::get('/auth/strava/redirect', [StravaController::class, 'handleStravaRedirect']);
Route::get('/auth/strava/callback', [StravaController::class, 'handleStravaCallback']);

