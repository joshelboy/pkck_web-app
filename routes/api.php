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

Route::middleware('auth:sanctum')->get('/tours/84d/{id}', function ($id) {
    $tours = DB::table('strava')
            ->select('*')
            ->where('athlete',  '=', $id)
            ->where('type', '=', 'Ride')
            ->orWhere('type', '=', 'VirtualRide')
            ->get();
    return $tours;
});

Route::middleware('auth:sanctum')->get('/strava_user', function () {
    $strava_user = DB::table('users')
            ->select('strava_id', 'name')
            ->get();

    return $strava_user;
});

Route::middleware('auth:sanctum')->get('/tours/gf/{id}', function ($id) {
    $gf = DB::table('strava')
            ->select(DB::raw('count (*)'))
            ->where('athlete', '=', $id)
            ->where(DB::raw('distance::NUMERIC'), '>', '100000')
            ->where('date', '>=', '2022-01-01')
            ->where('date', '<=', '2022-12-31')
            ->get();
    return $gf;
});

Route::middleware('auth:sanctum')->get('/tours/1y/{id}', function ($id) {
    $tours = DB::table('strava')
            ->select('*')
            ->where('athlete',  '=', $id)
            ->where('date', '>=', '2022-01-01')
            ->where('date', '<=', '2022-12-31')
            ->where('type', '=', 'Ride')
            ->orWhere('type', '=', 'VirtualRide')
            ->orderBy('date', 'ASC')
            ->get();
    return $tours;
});
