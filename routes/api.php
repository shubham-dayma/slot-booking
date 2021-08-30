<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

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

// To get future event list
Route::get('event-list', 'App\Http\Controllers\Api\EventController@index');

// To add participant for events
Route::post('add-participant', 'App\Http\Controllers\Api\ParticipantController@store');
