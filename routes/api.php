<?php

use App\Models\Roster\SoloCertification;
use App\Http\Controllers\PrimaryViewsController;
use Carbon\Carbon;
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

    return response()->json($array);
});

Route::get('roster', function (Request $request) {
    $array = \App\Models\Roster\RosterMember::all();
    $array->makeHidden(['id', 'user_id', 'certified_in_q3', 'certified_in_q4', 'created_at', 'updated_at', 'remarks', 'date_certified']);

    return response()->json($array);
});

Route::get('roster/solocertifications', function (Request $request) {
    $array = SoloCertification::where('expires', '>', Carbon::now())->with(['rosterMember'])->get();
    $array->makeHidden(['id', 'roster_member_id', 'created_at', 'updated_at', 'remarks', 'instructor_id']);

    return response()->json($array);
});

Route::get('update-homepage/general', [PrimaryViewsController::class, 'homeUpdate']);
Route::get('update-homepage/controllers/{status}', [PrimaryViewsController::class, 'updateControllers']);