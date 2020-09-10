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

use App\Jobs\UpdateDiscordUserRoles;
use Illuminate\Support\Facades\Notification;

Route::get('/', 'PrimaryViewsController@home')->name('index');
Route::get('/map', 'PrimaryViewsController@map')->name('map');
Route::get('/roster', 'Roster\RosterController@publicRoster')->name('roster.public');
Route::get('/staff', function() { return redirect(route('staff'), 301); });
Route::get('/atcresources', 'Publications\AtcResourcesController@index')->name('atcresources.index');
Route::view('/pilots', 'pilots.index');
Route::view('/pilots/oceanic-clearance', 'pilots.oceanic-clearance');
Route::view('/pilots/position-report', 'pilots.position-report');
Route::view('/pilots/tracks', 'pilots.tracks');
Route::view('/pilots/tutorial', 'pilots.tutorial');
Route::get('/policies', 'Publications\PublicationsController@policiesIndex')->name('policies');
Route::get('/meetingminutes', 'News\NewsController@minutesIndex')->name('meetingminutes');
Route::get('/bookings', 'ControllerBookings\ControllerBookingsController@indexPublic')->name('controllerbookings.public');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/changelog', 'changelog')->name('changelog');
Route::get('/events', 'Events\EventController@index')->name('events.index');
Route::get('/events/{slug}', 'Events\EventController@viewEvent')->name('events.view');
Route::view('/branding', 'branding')->name('branding');
Route::view('/eurosounds', 'eurosounds')->name('eurosounds');

//About
Route::prefix('about')->group(function () {
    Route::get('/', function() { return redirect(route('about.who-we-are'), 301); })->name('about.index');
    Route::view('/who-we-are', 'about.who-we-are')->name('about.who-we-are');
    Route::view('/core', 'about-core')->name('about.core');
    Route::get('/staff', 'Users\StaffListController@index')->name('staff');
});

Route::get('test', function() {
    UpdateDiscordUserRoles::dispatch();
});

//Authentication
Route::prefix('auth')->group(function () {
    Route::get('/sso/login', 'Auth\LoginController@ssoLogin')->middleware('guest')->name('auth.sso.login');
    Route::get('/sso/validate', 'Auth\LoginController@validateSsoLogin')->middleware('guest');
    Route::get('/connect/login', 'Auth\LoginController@connectLogin')->middleware('guest')->name('auth.connect.login');
    Route::get('/connect/validate', 'Auth\LoginController@validateConnectLogin')->middleware('guest');
    Route::get('/logout', 'Auth\LoginController@logout')->middleware('auth')->name('auth.logout');
});

//Discord shortcut
Route::get('/discord', 'Community\DiscordController@joinShortcut');

//Public news articles
Route::get('/news/{id}', 'News\NewsController@viewArticlePublic')->name('news.articlepublic')->where('id', '[0-9]+');
Route::get('/news/{slug}', 'News\NewsController@viewArticlePublic')->name('news.articlepublic');
Route::get('/news/', 'News\NewsController@viewAllPublic')->name('news');

//Base level authentication
Route::group(['middleware' => 'auth'], function () {

    //Privacy accept
    Route::get('/privacyaccept', 'Users\UserController@privacyAccept');
    Route::get('/privacydeny', 'Users\UserController@privacyDeny');

    //Dashboard
    Route::get('/dashboard', 'PrimaryViewsController@dashboard')->name('dashboard.index');

    //GDPR
    Route::get('/me/data', 'Users\DataController@index')->name('me.data');
    Route::post('/me/data/export/all', 'Users\DataController@exportAllData')->name('me.data.export.all');

    //Restricted role prohibitation
    Route::group(['middleware' => 'restricted'], function () {

        //Events
        Route::post('/dashboard/events/controllerapplications/ajax', 'Events\EventController@controllerApplicationAjaxSubmit')->name('events.controllerapplication.ajax');

        //Avatars/display name
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
        Route::get('/dashboard/tickets/staff', 'Tickets\TicketsController@staffIndex')->name('tickets.staff')->middleware('executive');
        Route::get('/dashboard/tickets/{id}', 'Tickets\TicketsController@viewTicket')->name('tickets.viewticket');
        Route::post('/dashboard/tickets', 'Tickets\TicketsController@startNewTicket')->name('tickets.startticket');
        Route::post('/dashboard/tickets/{id}', 'Tickets\TicketsController@addReplyToTicket')->name('tickets.reply');
        Route::get('/dashboard/tickets/{id}/close', 'Tickets\TicketsController@closeTicket')->name('tickets.closeticket');

        //Feedback
        Route::get('/feedback', 'Feedback\FeedbackController@create')->name('feedback.create');
        Route::post('/feedback', 'Feedback\FeedbackController@createPost')->name('feedback.create.post');

        //Email prefs
        Route::get('/dashboard/emailpref', 'Users\DataController@emailPref')->name('dashboard.emailpref');
        Route::get('/dashboard/emailpref/subscribe', 'Users\DataController@subscribeEmails');
        Route::get('/dashboard/emailpref/unsubscribe', 'Users\DataController@unsubscribeEmails');

        //Applications
        Route::get('/dashboard/application', 'AtcTraining\ApplicationsController@startApplicationProcess')->name('application.start');
        Route::post('/dashboard/application', 'AtcTraining\ApplicationsController@submitApplication')->name('application.submit');
        Route::get('/dashboard/application/list', 'AtcTraining\ApplicationsController@viewApplications')->name('application.list');
        Route::get('/dashboard/application/{application_id}', 'AtcTraining\ApplicationsController@viewApplication')->name('application.view');
        Route::get('/dashboard/application/{application_id}/withdraw', 'AtcTraining\ApplicationsController@withdrawApplication');

        //"Me"
        Route::get('/me/editbiography', 'Users\UserController@editBioIndex')->name('me.editbioindex');
        Route::post('/me/editbiography', 'Users\UserController@editBio')->name('me.editbio');
        Route::get('/me/discord/unlink', 'Community\DiscordController@unlinkDiscord')->name('me.discord.unlink');
        Route::get('/me/discord/link/callback/{param?}', 'Community\DiscordController@linkCallbackDiscord')->name('me.discord.link.callback');
        Route::get('/me/discord/link/{param?}', 'Community\DiscordController@linkRedirectDiscord')->name('me.discord.link');
        Route::get('/me/discord/server/join', 'Community\DiscordController@joinRedirectDiscord')->name('me.discord.join');
        Route::get('/me/discord/server/join/callback', 'Community\DiscordController@joinCallbackDiscord');
        Route::get('/me/preferences', 'Users\UserController@preferences')->name('me.preferences');
        Route::post('/me/preferences', 'Users\UserController@preferencesPost')->name('me.preferences.post');

        //Training
        Route::prefix('training')->group(function () {

            //Applications
            Route::get('applications', 'Training\ApplicationsController@showAll')->name('training.applications.showall');
            Route::get('applications/apply', 'Training\ApplicationsController@apply')->name('training.applications.apply');
            Route::post('applications/apply', 'Training\ApplicationsController@applyPost')->name('training.applications.apply.post');
            Route::post('applications/withdraw', 'Training\ApplicationsController@withdraw')->name('training.applications.withdraw');
            Route::post('applications/comment/post', 'Training\ApplicationsController@commentPost')->name('training.applications.comment.post');
            Route::get('applications/{reference_id}', 'Training\ApplicationsController@show')->name('training.applications.show');
            Route::get('applications/{reference_id}/updates', 'Training\ApplicationsController@showUpdates')->name('training.applications.show.updates');


        });

        //ATC Resources
        Route::post('/atcresources', 'Publications\AtcResourcesController@uploadResource')->name('atcresources.upload');
        Route::get('/atcresources/delete/{id}', 'Publications\AtcResourcesController@deleteResource')->name('atcresources.delete');


        //Roster
        Route::get('/dashboard/roster', 'AtcTraining\RosterController@index')->name('roster.index');
        Route::post('/dashboard/roster', 'AtcTraining\RosterController@addController')->name('roster.addcontroller');
        Route::post('/dashboard/roster/{id}', 'AtcTraining\RosterController@editController')->name('roster.editcontroller');
        Route::get('/dashboard/roster/{id}', 'AtcTraining\RosterController@viewController')->name('roster.viewcontroller');
        Route::get('/dashboard/roster/{cid}/delete', 'AtcTraining\RosterController@deleteController')->name('roster.deletecontroller');

        //Events
        Route::get('/admin/events', 'Events\EventController@adminIndex')->name('events.admin.index');
        Route::get('/admin/events/create', 'Events\EventController@adminCreateEvent')->name('events.admin.create');
        Route::post('/admin/events/create', 'Events\EventController@adminCreateEventPost')->name('events.admin.create.post');
        Route::post('/admin/events/{slug}/edit', 'Events\EventController@adminEditEventPost')->name('events.admin.edit.post');
        Route::post('/admin/events/{slug}/update/create', 'Events\EventController@adminCreateUpdatePost')->name('events.admin.update.post');
        Route::get('/admin/events/{slug}', 'Events\EventController@adminViewEvent')->name('events.admin.view');
        Route::get('/admin/events/{slug}/delete', 'Events\EventController@adminDeleteEvent')->name('events.admin.delete');
        Route::get('/admin/events/{slug}/controllerapps/{cid}/delete', 'Events\EventController@adminDeleteControllerApp')->name('events.admin.controllerapps.delete');
        Route::get('/admin/events/{slug}/updates/{id}/delete', 'Events\EventController@adminDeleteUpdate')->name('events.admin.update.delete');

        //Users


        //Controller Applications
        Route::get('/dashboard/training/applications', 'AtcTraining\TrainingController@viewAllApplications')->name('training.applications');
        Route::get('/dashboard/training/applications/{id}', 'AtcTraining\TrainingController@viewApplication')->name('training.viewapplication');
        Route::get('/dashboard/training/applications/{id}/accept', 'AtcTraining\TrainingController@acceptApplication')->name('training.application.accept');
        Route::get('/dashboard/training/applications/{id}/deny', 'AtcTraining\TrainingController@denyApplication')->name('training.application.deny');
        Route::post('/dashboard/training/applications/{id}/', 'AtcTraining\TrainingController@editStaffComment')->name('training.application.savestaffcomment');

        //AtcTraining
        Route::post('/dashboard/training/instructors', 'AtcTraining\TrainingController@addInstructor')->name('training.instructors.add');

        //Admin
        Route::prefix('admin')->group(function () {

            //Settings
            Route::prefix('settings')->group(function () {
                Route::get('/', 'Settings\SettingsController@index')->name('settings.index');
                Route::get('/site-information', 'Settings\SettingsController@siteInformation')->name('settings.siteinformation');
                Route::post('/site-information', 'Settings\SettingsController@saveSiteInformation')->name('settings.siteinformation.post');
                Route::get('/emails', 'Settings\SettingsController@emails')->name('settings.emails');
                Route::post('/emails', 'Settings\SettingsController@saveEmails')->name('settings.emails.post');
                Route::get('/activity-log', 'Settings\SettingsController@activityLog')->name('settings.activitylog');
                Route::get('/rotation-images', 'Settings\SettingsController@rotationImages')->name('settings.rotationimages');
                Route::get('/rotation-images/delete/{image_id}', 'Settings\SettingsController@deleteRotationImage')->name('settings.rotationimages.deleteimg');
                Route::post('/rotation-images/uploadimg', 'Settings\SettingsController@uploadRotationImage')->name('settings.rotationimages.uploadimg');
                Route::get('/staff', 'Users\StaffListController@editIndex')->name('settings.staff');
                Route::post('/staff/{id}', 'Users\StaffListController@editStaffMember')->name('settings.staff.editmember');
            });

            //News
            Route::prefix('news')->group(function () {
                Route::get('/', 'News\NewsController@index')->name('news.index');
                Route::get('/article/create', 'News\NewsController@createArticle')->name('news.articles.create');
                Route::post('/article/create', 'News\NewsController@postArticle')->name('news.articles.create.post');
                Route::get('/article/{slug}', 'News\NewsController@viewArticle')->name('news.articles.view');
                Route::get('/announcement/create', 'News\NewsController@createAnnouncement')->name('news.announcements.create');
                Route::post('/announcement/create', 'News\NewsController@createAnnouncementPost')->name('news.announcements.create.post');
                Route::get('/announcement/{slug}', 'News\NewsController@viewAnnouncement')->name('news.announcements.view');
            });

            //Publications
            Route::prefix('publications')->group(function () {
                Route::get('/', 'Publications\PublicationsController@adminIndex')->name('publications.index');
                Route::get('/policy/create', 'Publications\PublicationsController@adminCreatePolicy')->name('publications.policies.create');
                Route::post('/policy/create', 'Publications\PublicationsController@adminCreatePolicyPost')->name('publications.policies.create.post');
                Route::get('/policy/{id}', 'Publications\PublicationsController@adminViewPolicy')->name('publications.policies.view');
            });

            //Network
            Route::prefix('network')->group(function () {
                Route::get('/', 'Network\NetworkController@index')->name('network.index');
                Route::get('/monitored-positions', 'Network\NetworkController@monitoredPositionsIndex')->name('network.monitoredpositions.index');
                Route::get('/monitored-positions/{position}', 'Network\NetworkController@viewMonitoredPosition')->name('network.monitoredpositions.view');
                Route::post('/monitored-positions/create', 'Network\NetworkController@createMonitoredPosition')->name('network.monitoredpositions.create');
            });

            //Community
            Route::prefix('community')->group(function () {
                //User Management
                Route::get('/users', 'Community\UsersController@index')->name('community.users.index');
                Route::get('/users/{id}', 'Community\UsersController@viewUser')->name('community.users.view');
                Route::post('/users/{id}/assign/role', 'Community\UsersController@assignUserRole')->name('community.users.assign.role');
                Route::post('/users/{id}/assign/permission', 'Community\UsersController@assignUserPermission')->name('community.users.assign.permission');
                Route::delete('/users/{id}/remove/role', 'Community\UsersController@removeUserRole')->name('community.users.remove.role');
                Route::delete('/users/{id}/remove/permission', 'Community\UsersController@removeUserPermission')->name('community.users.remove.permission');


                Route::post('/discord/discordban', 'Community\DiscordController@createDiscordBan')->name('discord.createban');
            });

        });

    });

});
