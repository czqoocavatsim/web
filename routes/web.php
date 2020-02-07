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

use Illuminate\Support\Facades\Notification;

Route::get('/', 'HomeController@view')->name('index');
Route::get('/map', 'HomeController@map')->name('map');
Route::get('/roster', 'AtcTraining\RosterController@showPublic')->name('roster.public');
Route::get('/staff', 'Users\StaffListController@index')->name('staff');
Route::get('/atcresources', 'Publications\AtcResourcesController@index')->name('atcresources.index');
Route::view('/pilots', 'pilots.index');
Route::view('/pilots/oceanic-clearance', 'pilots.oceanic-clearance');
Route::view('/pilots/position-report', 'pilots.position-report');
Route::view('/pilots/tracks', 'pilots.tracks');
Route::view('/pilots/tutorial', 'pilots.tutorial');
Route::get('/policies', 'Publications\PoliciesController@index')->name('policies');
Route::get('/meetingminutes', 'News\NewsController@minutesIndex')->name('meetingminutes');
Route::get('/bookings', 'ControllerBookings\ControllerBookingsController@indexPublic')->name('controllerbookings.public');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/changelog', 'changelog')->name('changelog');
Route::view('/emailtest', 'emails.announcement');
Route::get('/events', 'Events\EventController@index')->name('events.index');
Route::get('/events/{slug}', 'Events\EventController@viewEvent')->name('events.view');
Route::view('/about', 'about')->name('about');
Route::view('/branding', 'branding')->name('branding');

Route::get('/test', function () {
    echo PHP_OS;
});

//Authentication
Route::prefix('auth')->group(function () {
    Route::get('/sso/login', 'Auth\LoginController@ssoLogin')->middleware('guest')->name('auth.sso.login');
    Route::get('/sso/validate', 'Auth\LoginController@validateSsoLogin')->middleware('guest');
    Route::get('/connect/login', 'Auth\LoginController@connectLogin')->middleware('guest')->name('auth.connect.login');
    Route::get('/connect/validate', 'Auth\LoginController@validateConnectLogin')->middleware('guest');
    Route::get('/logout', 'Auth\LoginController@logout')->middleware('auth')->name('auth.logout');
});


//Public news articles
Route::get('/news/{id}', 'News\NewsController@viewArticlePublic')->name('news.articlepublic')->where('id', '[0-9]+');
Route::get('/news/{slug}', 'News\NewsController@viewArticlePublic')->name('news.articlepublic');
Route::get('/news/', 'News\NewsController@viewAllPublic')->name('news');

//Base level authentication
Route::group(['middleware' => 'auth'], function () {
    //Privacy accept
    Route::get('/privacyaccept', 'Users\UserController@privacyAccept');
    Route::get('/privacydeny', 'Users\UserController@privacyDeny');
    //Events
    Route::post('/dashboard/events/controllerapplications/ajax', 'Events\EventController@controllerApplicationAjaxSubmit')->name('events.controllerapplication.ajax');
    //Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::post('/users/changeavatar', 'Users\UserController@changeAvatar')->name('users.changeavatar');
    Route::get('/users/changeavatar/discord', 'Users\UserController@changeAvatarDiscord')->name('users.changeavatar.discord');
    Route::get('/users/resetavatar', 'Users\UserController@resetAvatar')->name('users.resetavatar');
    Route::post('/users/changedisplayname', 'Users\UserController@changeDisplayName')->name('users.changedisplayname');
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
    Route::get('/notification/{id}', 'Users\NotificationRedirectController@notificationRedirect')->name('notification.redirect');
    Route::get('/notificationclear', 'Users\NotificationRedirectController@clearAll');
    //Tickets
    Route::get('/dashboard/tickets', 'Tickets\TicketsController@index')->name('tickets.index');
    Route::get('/dashboard/tickets/staff', 'Tickets\TicketsController@staffIndex')->name('tickets.staff');
    Route::get('/dashboard/tickets/{id}', 'Tickets\TicketsController@viewTicket')->name('tickets.viewticket');
    Route::post('/dashboard/tickets', 'Tickets\TicketsController@startNewTicket')->name('tickets.startticket');
    Route::post('/dashboard/tickets/{id}', 'Tickets\TicketsController@addReplyToTicket')->name('tickets.reply');
    Route::get('/dashboard/tickets/{id}/close', 'Tickets\TicketsController@closeTicket')->name('tickets.closeticket');
    //Email prefs
    Route::get('/dashboard/emailpref', 'Users\DataController@emailPref')->name('dashboard.emailpref');
    Route::get('/dashboard/emailpref/subscribe', 'Users\DataController@subscribeEmails');
    Route::get('/dashboard/emailpref/unsubscribe', 'Users\DataController@unsubscribeEmails');
    //GDPR
    Route::get('/dashboard/me/data', 'Users\DataController@index')->name('me.data');
    Route::post('/dashboard/me/data/export/all', 'Users\DataController@exportAllData')->name('me.data.export.all');
    //Applications
    Route::group(['middleware' => 'notcertified'], function () {
        Route::get('/dashboard/application', 'AtcTraining\ApplicationsController@startApplicationProcess')->name('application.start');
        Route::post('/dashboard/application', 'AtcTraining\ApplicationsController@submitApplication')->name('application.submit');
    });
    Route::get('/dashboard/application/list', 'AtcTraining\ApplicationsController@viewApplications')->name('application.list');
    Route::get('/dashboard/application/{application_id}', 'AtcTraining\ApplicationsController@viewApplication')->name('application.view');
    Route::get('/dashboard/application/{application_id}/withdraw', 'AtcTraining\ApplicationsController@withdrawApplication');
    //"Me"
    Route::get('/dashboard/me/editbiography', 'Users\UserController@editBioIndex')->name('me.editbioindex');
    Route::post('/dashboard/me/editbiography', 'Users\UserController@editBio')->name('me.editbio');
    Route::get('/dashboard/me/user/{id}', 'Users\UserController@viewUserProfilePublic')->name('me.profile.public');
    Route::get('/dashboard/me/discord/link', 'Users\UserController@linkDiscord')->name('me.discord.link');
    Route::get('/dashboard/me/discord/unlink', 'Users\UserController@unlinkDiscord')->name('me.discord.unlink');
    Route::get('/dashboard/me/discord/link/redirect', 'Users\UserController@linkDiscordRedirect')->name('me.discord.link.redirect');
    Route::get('/dashboard/me/discord/server/join', 'Users\UserController@joinDiscordServerRedirect')->name('me.discord.join');
    Route::get('/dashboard/me/discord/server/join/redirect', 'Users\UserController@joinDiscordServer');
    //Bookings
    Route::group(['middleware' => 'certified'], function () {
        Route::get('/dashboard/bookings', 'ControllerBookings\ControllerBookingsController@index')->name('controllerbookings.index');
        Route::get('/dashboard/bookings/create', 'ControllerBookings\ControllerBookingsController@create')->name('controllerbookings.create');
        Route::post('/dashboard/bookings/create', 'ControllerBookings\ControllerBookingsController@createPost')->name('controllerbookings.create.post');
    });
    //AtcTraining
    Route::get('/dashboard/training', 'AtcTraining\TrainingController@index')->name('training.index');
    Route::group(['middleware' => 'instructor'], function () {
        Route::get('/dashboard/training/sessions', 'AtcTraining\TrainingController@instructingSessionsIndex')->name('training.instructingsessions.index');
        Route::get('/dashboard/training/sessions/{id}', 'AtcTraining\TrainingController@viewInstructingSession')->name('training.instructingsessions.viewsession');
        Route::view('/dashboard/training/sessions/create', 'dashboard.training.instructingsessions.create')->name('training.instructingsessions.createsessionindex');
        Route::get('/dashboard/training/sessions/create', 'AtcTraining\TrainingController@createInstructingSession')->name('training.instructingsessions.createsession');
        Route::get('/dashboard/training/instructors', 'AtcTraining\TrainingController@instructorsIndex')->name('training.instructors');
        Route::get('/dashboard/training/students/current', 'AtcTraining\TrainingController@currentStudents')->name('training.students.current');
        Route::get('/dashboard/training/students/{id}', 'AtcTraining\TrainingController@viewStudent')->name('training.students.view');
        Route::post('/dashboard/training/students/{id}/assigninstructor', 'AtcTraining\TrainingController@assignInstructorToStudent')->name('training.students.assigninstructor');
        Route::post('/dashboard/training/students/{id}/setstatus', 'AtcTraining\TrainingController@changeStudentStatus')->name('training.students.setstatus');
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
        Route::post('/atcresources', 'Publications\AtcResourcesController@uploadResource')->name('atcresources.upload');
        Route::get('/atcresources/delete/{id}', 'Publications\AtcResourcesController@deleteResource')->name('atcresources.delete');
        //News
        Route::get('/dashboard/news', 'News\NewsController@index')->name('news.index');
        Route::get('/dashboard/news/article/create', 'News\NewsController@createArticle')->name('news.articles.create');
        //Roster
        Route::get('/dashboard/roster', 'AtcTraining\RosterController@index')->name('roster.index');
        Route::post('/dashboard/roster', 'AtcTraining\RosterController@addController')->name('roster.addcontroller');
        Route::post('/dashboard/roster/{id}', 'AtcTraining\RosterController@editController')->name('roster.editcontroller');
        Route::get('/dashboard/roster/{id}', 'AtcTraining\RosterController@viewController')->name('roster.viewcontroller');
        Route::get('/dashboard/roster/{cid}/delete', 'AtcTraining\RosterController@deleteController')->name('roster.deletecontroller');
        //Events
        Route::get('/dashboard/events', 'Events\EventController@adminIndex')->name('events.admin.index');
        Route::get('/dashboard/events/{slug}', 'Events\EventController@adminViewEvent')->name('events.admin.view');
        //Users
        Route::get('/dashboard/users/', 'Users\UserController@viewAllUsers')->middleware('director')->name('users.viewall');
        Route::post('/dashboard/users/search/ajax', 'Users\UserController@searchUsers')->name('users.search.ajax');
        Route::get('/dashboard/users/{id}', 'Users\UserController@viewUserProfile')->name('users.viewprofile');
        Route::post('/dashboard/users/{id}', 'Users\UserController@createUserNote')->name('users.createnote');
        Route::get('/dashboard/users/{user_id}/note/{note_id}/delete', 'Users\UserController@deleteUserNote')->name('users.deletenote');
        Route::group(['middleware' => 'executive'], function () {
            Route::post('/dashboard/users/func/avatarchange', 'Users\UserController@changeUsersAvatar')->name('users.changeusersavatar');
            Route::post('/dashboard/users/func/avatarreset', 'Users\UserController@resetUsersAvatar')->name('users.resetusersavatar');
            Route::post('/dashboard/users/func/bioreset', 'Users\UserController@resetUsersBio')->name('users.resetusersbio');
            Route::get('/dashboard/users/{id}/delete', 'Users\UserController@deleteUser');
            Route::get('/dashboard/users/{id}/edit', 'Users\UserController@editUser')->name('users.edit.create');
            Route::post('/dashboard/users/{id}/edit', 'Users\UserController@storeEditUser')->name('users.edit.store');
            Route::post('/dashboard/users/{id}/bookingban/create', 'Users\UserController@createBookingBan')->name('users.bookingban.create');
            Route::post('/dashboard/users/{id}/bookingban/remove', 'Users\UserController@removeBookingBan')->name('users.bookingban.remove');
        });
        Route::get('/dashboard/users/{id}/email', 'Users\UserController@emailCreate')->name('users.email.create');
        Route::get('/dashboard/users/{id}/email', 'Users\UserController@emailStore')->name('users.email.store');
        //Controller Applications
        Route::get('/dashboard/training/applications', 'AtcTraining\TrainingController@viewAllApplications')->name('training.applications');
        Route::get('/dashboard/training/applications/{id}', 'AtcTraining\TrainingController@viewApplication')->name('training.viewapplication');
        Route::group(['middleware' => 'executive'], function () {
            Route::get('/dashboard/training/applications/{id}/accept', 'AtcTraining\TrainingController@acceptApplication')->name('training.application.accept');
            Route::get('/dashboard/training/applications/{id}/deny', 'AtcTraining\TrainingController@denyApplication')->name('training.application.deny');
            Route::post('/dashboard/training/applications/{id}/', 'AtcTraining\TrainingController@editStaffComment')->name('training.application.savestaffcomment');
        });
        //AtcTraining
        Route::post('/dashboard/training/instructors', 'AtcTraining\TrainingController@addInstructor')->name('training.instructors.add');
        //Minutes
        Route::get('/meetingminutes/{id}', 'News\NewsController@minutesDelete')->name('meetingminutes.delete');
        Route::post('/meetingminutes', 'News\NewsController@minutesUpload')->name('meetingminutes.upload');
        //Network
        Route::get('/dashboard/network', 'Network\NetworkController@index')->name('network.index');
        Route::get('/dashboard/network/monitoredpositions', 'Network\NetworkController@monitoredPositionsIndex')->name('network.monitoredpositions.index');
        Route::get('/dashboard/network/monitoredpositions/{position}', 'Network\NetworkController@viewMonitoredPosition')->name('network.monitoredpositions.view');
        Route::post('/dashboard/network/monitoredpositions/create', 'Network\NetworkController@createMonitoredPosition')->name('network.monitoredpositions.create');
        //Audit Log and settings, and policy creation
        Route::group(['middleware' => 'executive'], function () {
            Route::get('/dashboard/auditlog', 'Settings\AuditLogController@index')->name('auditlog');
            Route::post('/dashboard/auditlog', 'Settings\AuditLogController@insert')->name('auditlog.insert');
            Route::get('/dashboard/coresettings', 'Settings\CoreSettingsController@index')->name('coresettings');
            Route::get('/dashboard/coresettings/enablemaintenance', 'Settings\CoreSettingsController@enableMaintenance')->name('coresettings.enablemaintenance');
            Route::post('/dashboard/coresettings', 'Settings\CoreSettingsController@store')->name('coresettings.store');
            Route::get('/dashboard/coresettings/ip/{id}/del', 'Settings\CoreSettingsController@deleteExemptIp')->name('coresettings.exemptips.delete');
            Route::post('/dashboard/coresettings/ip/add', 'Settings\CoreSettingsController@addExemptIp')->name('coresettings.exemptips.add');
            Route::post('/policies', 'Publications\PoliciesController@addPolicy')->name('policies.create');
            Route::get('/policies/{id}/delete', 'Publications\PoliciesController@deletePolicy');
            Route::get('/dashboard/staff', 'Users\StaffListController@editIndex')->name('staff.edit');
            Route::post('/dashboard/staff/{id}', 'Users\StaffListController@editStaffMember')->name('staff.editmember');
        });
    });
});
