<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/tours/{id}', function ($id) {
    $tours = DB::table('strava')
            ->select('*')
            ->where('athlete',  '=', $id)
            ->get();

    return $tours;
});

Route::middleware('auth:sanctum')->get('/strava_user', function () {
    $strava_user = DB::table('users')
            ->select('strava_id', 'strava_name')
            ->get();

    return $strava_user;
});