<?php

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


//Standard site routes

Route::get('/', 'HomeController@view');

Route::get('/roster', 'RosterController@showPublic');

Route::get('/staff', function () {
    return view('staff');
});

Route::get('/sector-files', function () {
    return view('sector-files');
});

Route::get('/pilots', function () {
    return view('pilots.index');
});

Route::get('/pilots/oceanic-clearance', function () {
    return view('pilots.oceanic-clearance');
});

Route::get('/pilots/position-report', function () {
    return view('pilots.position-report');
});

Route::get('/pilots/tracks', function () {
    return view('pilots.tracks');
});


Route::get('/pilots/vatsim-resources', function () {
    return view('pilots.vatsim-resources');
});

Route::get('/publications', function () {
    return view('publications');
});

Route::group(['middleware' => 'auth'], function()
{

    Route::get('/dashboard/feedback', 'FeedbackController@create')->name('feedback.create');

    Route::get('/dashboard/feedback/submitted', 'FeedbackController@submitted')->name('feedback.submitted');

    Route::get('/dashboard/feedback/submitted', 'FeedbackController@submitted')->name('feedback.submitted');

    Route::post('/dashboard/feedback', 'FeedbackController@store')->name('feedback.store');

    Route::get('/dashboard/data', 'GDPRController@create')->name('data.create');
    Route::get('/dashboard/data/submitted', 'GDPRController@submitted')->name('data.submitted');
    Route::post('/dashboard/data', 'GDPRController@store')->name('data.store');

    Route::get('/dashboard', function (){
        return view('dashboard/home');
    });



    Route::get('/dashboard/roster', function (){
        return view('dashboard/roster/view');
    });

    Route::get('/dashboard/roster/add', function (){
        return view('dashboard/roster/add');
    });

    Route::get('/dashboard/roster/edit', function (){
        return view('dashboard/roster/edit');
    });

    Route::get('/dashboard/roster/autoupdate', function (){
        return view('dashboard/roster/autoupdate');
    });

    Route::get('/dashboard/application/create', 'ApplicationsController@create')->name('application.create');
    Route::post('/dashboard/application/create', 'ApplicationsController@store')->name('application.store');
    Route::get('/dashboard/application/status', 'ApplicationsController@viewStatus')->name('application.status');
    Route::get('/dashboard/application/{application_id}', 'ApplicationsController@viewApplication');
    Route::get('/dashboard/application/{application_id}/withdraw', 'ApplicationsController@withdrawApplication');
    Route::get('/dashboard/users/viewall', 'UserController@viewAllUsers')->middleware('director')->name('users.viewall');
    Route::get('/dashboard/users/{id}', 'UserController@viewUserProfile');
    Route::get('/dashboard/users/{id}/email', 'UserController@emailCreate')->name('users.email.create');
    Route::get('/dashboard/users/{id}/email', 'UserController@emailStore')->name('users.email.store');
});



Route::get('/sausages', function (){
    return view('sausages');
});

Route::get('/privacy', function (){
    return view('privacy');
});




Route::get('/errors/alreadyapplied', function (){
    return view('errors/alreadyapplied');
});

Route::get('/emails/feedback', function (){
    return view('emails/feedback');
});



Route::get('/sparkpost', function () {
    Mail::send('emails.feedback', [], function ($message) {
        $message
            ->from('no-reply@czqo.vatcan.ca', 'Your Name')
            ->to('lieselta@gmail.com', 'Receiver Name')
            ->subject('From SparkPost with ❤');
    });
});


Route::group(['middleware' => 'auth'], function()
{
    Route::get('/discord/sso', ['as'=>'senddiscord', 'uses'=>'DiscordController@senddiscord']);
    Route::get('/discord/process', ['as'=>'process', 'uses'=>'DiscordController@process']);
    Route::get('/discord/assignperms', ['as'=>'assignperms', 'uses'=>'DiscordController@assignperms']);
});


Route::get('/login', 'LoginController@login')->middleware('guest')->name('login');

Route::get('/validate', 'LoginController@validateLogin')->middleware('guest');

Route::get('/logout', 'LoginController@logout')->middleware('auth')->name('logout');

