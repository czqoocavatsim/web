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

//Public views
Route::get('/', 'HomeController@view')->name('index');
Route::get('/roster', 'RosterController@showPublic')->name('roster.public');
Route::get('/staff', 'StaffListController@index')->name('staff');
Route::get('/atcresources', 'AtcResourcesController@index')->name('atcresources.index');
Route::view('/pilots', 'pilots.index');
Route::view('/pilots/oceanic-clearance', 'pilots.oceanic-clearance');
Route::post('/pilots/oceanic-clearance', 'PilotToolsController@generateOceanicController')->name('pilots.generateclearance');
Route::view('/pilots/position-report', 'pilots.position-report');
Route::view('/pilots/tracks', 'pilots.tracks');
Route::view('/pilots/tutorial', 'pilots.tutorial');
Route::get('/policies', 'PoliciesController@index')->name('policies');
Route::get('/meetingminutes', 'NewsController@minutesIndex')->name('meetingminutes');
Route::get('/bookings', 'ControllerBookingsController@indexPublic')->name('controllerbookings.public');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/changelog', 'changelog')->name('changelog');
Route::view('/emailtest', 'emails.announcement');
Route::view('/about', 'about')->name('about');

//Authentication
Route::get('/login', 'LoginController@login')->middleware('guest')->name('login');
Route::get('/logintest', function() {
    Auth::login(\App\User::find(1364284));
});
Route::get('/validate', 'LoginController@validateLogin')->middleware('guest');
Route::get('/logout', 'LoginController@logout')->middleware('auth')->name('logout');

//Public news articles
Route::get('/news/{id}', 'NewsController@viewPublicArticleInt')->name('news.articlepublic')->where('id', '[0-9]+');
Route::get('/news/{slug}', 'NewsController@viewPublicArticle')->name('news.articlepublic');
Route::get('/news/', 'NewsController@viewPublicAll')->name('news.allpublic');

//Base level authentication
Route::group(['middleware' => 'auth'], function () {
    //Privacy accept
    Route::get('/privacyaccept', 'UserController@privacyAccept');
    Route::get('/privacydeny', 'UserController@privacyDeny');

    //Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::post('/dashboard', 'UserController@changeAvatar')->name('users.changeavatar');
    Route::get('/users/resetavatar', 'UserController@resetAvatar')->name('users.resetavatar');
    Route::post('/users/changedisplayname', 'UserController@changeDisplayName')->name('users.changedisplayname');
    Route::get('/users/defaultavatar/{id}', function ($id) {
        $user = \App\User::whereId($id)->firstOrFail();
        if ($user->isAvatarDefault()) {
            return true;
        }
        return false;
    });

    //CTP
    //Route::post('/dashboard/ctp/signup/post', 'DashboardController@ctpSignUp')->name('ctp.signup.post');
    //Notification
    Route::get('/notification/{id}', 'NotificationRedirectController@notificationRedirect')->name('notification.redirect');
    Route::get('/notificationclear', 'NotificationRedirectController@clearAll');
    //Tickets
    Route::get('/dashboard/tickets', 'TicketsController@index')->name('tickets.index');
    Route::get('/dashboard/tickets/staff', 'TicketsController@staffIndex')->name('tickets.staff');
    Route::get('/dashboard/tickets/{id}', 'TicketsController@viewTicket')->name('tickets.viewticket');
    Route::post('/dashboard/tickets', 'TicketsController@startNewTicket')->name('tickets.startticket');
    Route::post('/dashboard/tickets/{id}', 'TicketsController@addReplyToTicket')->name('tickets.reply');
    Route::get('/dashboard/tickets/{id}/close', 'TicketsController@closeTicket')->name('tickets.closeticket');
    //Email prefs
    Route::get('/dashboard/emailpref', 'GDPRController@emailPref')->name('dashboard.emailpref');
    Route::get('/dashboard/emailpref/subscribe', 'GDPRController@subscribeEmails');
    Route::get('/dashboard/emailpref/unsubscribe', 'GDPRController@unsubscribeEmails');
    //GDPR
    Route::get('/dashboard/data', 'GDPRController@create')->name('data.create');
    Route::get('/dashboard/data/submitted', 'GDPRController@submitted')->name('data.submitted');
    Route::post('/dashboard/data', 'GDPRController@store')->name('data.store');
    Route::get('/dashboard/data/remove', 'GDPRController@removeData')->name('data.remove.create');
    Route::post('/dashboard/data/remove', 'GDPRController@removeDataStore')->name('data.remove.store');
    Route::get('/dashboard/data/download', 'GDPRController@downloadData');
    //Applications
    Route::group(['middleware' => 'notcertified'], function () {
        Route::get('/dashboard/application', 'ApplicationsController@startApplicationProcess')->name('application.start');
        Route::post('/dashboard/application', 'ApplicationsController@submitApplication')->name('application.submit');
    });
    Route::get('/dashboard/application/list', 'ApplicationsController@viewApplications')->name('application.list');
    Route::get('/dashboard/application/{application_id}', 'ApplicationsController@viewApplication')->name('application.view');
    Route::get('/dashboard/application/{application_id}/withdraw', 'ApplicationsController@withdrawApplication');
    //"Me"
    Route::get('/dashboard/me/editbiography', 'UserController@editBioIndex')->name('me.editbioindex');
    Route::post('/dashboard/me/editbiography', 'UserController@editBio')->name('me.editbio');
    Route::get('/dashboard/me/user/{id}', 'UserController@viewUserProfilePublic')->name('me.profile.public');
    //Bookings
    Route::group(['middleware' => 'certified'], function () {
        Route::get('/dashboard/bookings', 'ControllerBookingsController@index')->name('controllerbookings.index');
        Route::get('/dashboard/bookings/create', 'ControllerBookingsController@create')->name('controllerbookings.create');
        Route::post('/dashboard/bookings/create', 'ControllerBookingsController@createPost')->name('controllerbookings.create.post');
    });
    //Training
    Route::get('/dashboard/training', 'TrainingController@index')->name('training.index');
    Route::group(['middleware' => 'instructor'], function () {
        Route::get('/dashboard/training/sessions', 'TrainingController@instructingSessionsIndex')->name('training.instructingsessions.index');
        Route::get('/dashboard/training/sessions/{id}', 'TrainingController@viewInstructingSession')->name('training.instructingsessions.viewsession');
        Route::view('/dashboard/training/sessions/create', 'dashboard.training.instructingsessions.create')->name('training.instructingsessions.createsessionindex');
        Route::get('/dashboard/training/sessions/create', 'TrainingController@createInstructingSession')->name('training.instructingsessions.createsession');
        Route::get('/dashboard/training/instructors', 'TrainingController@instructorsIndex')->name('training.instructors');
        Route::get('/dashboard/training/students/current', 'TrainingController@currentStudents')->name('training.students.current');
        Route::get('/dashboard/training/students/{id}', 'TrainingController@viewStudent')->name('training.students.view');
        Route::post('/dashboard/training/students/{id}/assigninstructor', 'TrainingController@assignInstructorToStudent')->name('training.students.assigninstructor');
        Route::post('/dashboard/training/students/{id}/setstatus', 'TrainingController@changeStudentStatus')->name('training.students.setstatus');
    });
    //Staff
    Route::group(['middleware' => 'director'], function () {
        Route::get('/dashboard/ctp/signups', function () {
            $signups = \App\CtpSignUp::all();
            foreach ($signups as $s) {
                echo $s.'<br/>';
            }
        })->name('ctp.signup.list');
        //ATC Resources
        Route::post('/atcresources', 'AtcResourcesController@uploadResource')->name('atcresources.upload');
        Route::get('/atcresources/delete/{id}', 'AtcResourcesController@deleteResource')->name('atcresources.delete');
        //News
        Route::get('/dashboard/news', 'NewsController@index')->name('news.index');
        Route::get('/dashboard/news/article/create', 'NewsController@createArticle')->name('news.articles.create');
        //Roster
        Route::get('/dashboard/roster', 'RosterController@index')->name('roster.index');
        Route::post('/dashboard/roster', 'RosterController@addController')->name('roster.addcontroller');
        Route::post('/dashboard/roster/{id}', 'RosterController@editController')->name('roster.editcontroller');
        Route::get('/dashboard/roster/{id}', 'RosterController@viewController')->name('roster.viewcontroller');
        Route::get('/dashboard/roster/{cid}/delete', 'RosterController@deleteController')->name('roster.deletecontroller');
        //Users
        Route::get('/dashboard/users/', 'UserController@viewAllUsers')->middleware('director')->name('users.viewall');
        Route::post('/dashboard/users/search/ajax', 'UserController@searchUsers')->name('users.search.ajax');
        Route::get('/dashboard/users/{id}', 'UserController@viewUserProfile')->name('users.viewprofile');
        Route::post('/dashboard/users/{id}', 'UserController@createUserNote')->name('users.createnote');
        Route::get('/dashboard/users/{user_id}/note/{note_id}/delete', 'UserController@deleteUserNote')->name('users.deletenote');
        Route::group(['middleware' => 'executive'], function () {
            Route::post('/dashboard/users/func/avatarchange', 'UserController@changeUsersAvatar')->name('users.changeusersavatar');
            Route::post('/dashboard/users/func/avatarreset', 'UserController@resetUsersAvatar')->name('users.resetusersavatar');
            Route::post('/dashboard/users/func/bioreset', 'UserController@resetUsersBio')->name('users.resetusersbio');
            Route::get('/dashboard/users/{id}/delete', 'UserController@deleteUser');
            Route::get('/dashboard/users/{id}/edit', 'UserController@editUser')->name('users.edit.create');
            Route::post('/dashboard/users/{id}/edit', 'UserController@storeEditUser')->name('users.edit.store');
            Route::post('/dashboard/users/{id}/bookingban/create', 'UserController@createBookingBan')->name('users.bookingban.create');
            Route::post('/dashboard/users/{id}/bookingban/remove', 'UserController@removeBookingBan')->name('users.bookingban.remove');
        });
        Route::get('/dashboard/users/{id}/email', 'UserController@emailCreate')->name('users.email.create');
        Route::get('/dashboard/users/{id}/email', 'UserController@emailStore')->name('users.email.store');
        //Controller Applications
        Route::get('/dashboard/training/applications', 'TrainingController@viewAllApplications')->name('training.applications');
        Route::get('/dashboard/training/applications/{id}', 'TrainingController@viewApplication')->name('training.viewapplication');
        Route::group(['middleware' => 'executive'], function () {
            Route::get('/dashboard/training/applications/{id}/accept', 'TrainingController@acceptApplication')->name('training.application.accept');
            Route::get('/dashboard/training/applications/{id}/deny', 'TrainingController@denyApplication')->name('training.application.deny');
            Route::post('/dashboard/training/applications/{id}/', 'TrainingController@editStaffComment')->name('training.application.savestaffcomment');
        });
        //Training
        Route::post('/dashboard/training/instructors', 'TrainingController@addInstructor')->name('training.instructors.add');
        //Minutes
        Route::get('/meetingminutes/{id}', 'NewsController@minutesDelete')->name('meetingminutes.delete');
        Route::post('/meetingminutes', 'NewsController@minutesUpload')->name('meetingminutes.upload');
        //Network
        //positions
        Route::get('/dashboard/network/positions', 'NetworkController@positionsIndex')->name('network.positions.index');
        Route::post('/dashboard/network/positions', 'NetworkController@addPosition')->name('network.positions.add');
        Route::get('/dashboard/network/position/{id}', 'NetworkController@viewPosition')->name('network.positions.view');
        Route::post('/dashboard/network/position/{id}', 'NetworkController@editPosition')->name('network.positions.edit');
        Route::post('/dashboard/network/position/{id}/del', 'NetworkController@deletePosition')->name('network.positions.delete');
        //Audit Log and settings, and policy creation
        Route::group(['middleware' => 'executive'], function () {
            Route::get('/dashboard/auditlog', 'AuditLogController@index')->name('auditlog');
            Route::post('/dashboard/auditlog', 'AuditLogController@insert')->name('auditlog.insert');
            Route::get('/dashboard/coresettings', 'CoreSettingsController@index')->name('coresettings');
            Route::get('/dashboard/coresettings/enablemaintenance', 'CoreSettingsController@enableMaintenance')->name('coresettings.enablemaintenance');
            Route::post('/dashboard/coresettings', 'CoreSettingsController@store')->name('coresettings.store');
            Route::get('/dashboard/coresettings/ip/{id}/del', 'CoreSettingsController@deleteExemptIp')->name('coresettings.exemptips.delete');
            Route::post('/dashboard/coresettings/ip/add', 'CoreSettingsController@addExemptIp')->name('coresettings.exemptips.add');
            Route::post('/policies', 'PoliciesController@addPolicy')->name('policies.create');
            Route::get('/policies/{id}/delete', 'PoliciesController@deletePolicy');
            Route::get('/dashboard/staff', 'StaffListController@editIndex')->name('staff.edit');
            Route::post('/dashboard/staff/{id}', 'StaffListCOntroller@editStaffMember')->name('staff.editmember');
        });
    });
});
