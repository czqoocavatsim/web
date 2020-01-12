<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('news', function (Request $request) {
    $array = \App\Models\News\News::where('certification', false)->where('visible', true)->get()->sortByDesc('published');
    $array->makeHidden(['created_at', 'updated_at', 'email_level', 'certification', 'user_id', 'show_author', 'visible', 'id']);
    return $array->toJson(JSON_PRETTY_PRINT);
});

Route::get('roster', function (Request $request) {
    $array = \App\Models\AtcTraining\RosterMember::all();
    $array->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);
    return $array->toJson(JSON_PRETTY_PRINT);
});
