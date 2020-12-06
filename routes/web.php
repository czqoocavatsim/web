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

use App\Models\Users\User;
use Illuminate\Support\Facades\Route;
use Thujohn\Twitter\Facades\Twitter;

Route::get('/', 'PrimaryViewsController@home')->name('index');
Route::get('/map', 'PrimaryViewsController@map')->name('map');
Route::get('/roster', 'Roster\RosterController@publicRoster')->name('roster.public');
Route::get('/roster/solo-certs', 'Training\SoloCertificationsController@public')->name('solocertifications.public');
Route::get('/staff', function() { return redirect(route('staff'), 301); });
Route::get('/atc-resources', 'Publications\PublicationsController@atcResources')->name('atcresources.index');
Route::view('/pilots', 'pilots.index');
Route::view('/pilots/oceanic-clearance', 'pilots.oceanic-clearance');
Route::view('/pilots/position-report', 'pilots.position-report');
Route::view('/pilots/tracks', 'pilots.tracks');
Route::view('/pilots/tracks/event', 'pilots.event-tracks');
Route::get('/policies', 'Publications\PublicationsController@policies')->name('policies');
Route::get('/privacy', function() { return redirect(route('policies'), 301); })->name('privacy');
Route::get('/events', 'Events\EventController@index')->name('events.index');
Route::get('/events/{slug}', 'Events\EventController@viewEvent')->name('events.view');

Route::get('/test', function () {
    $user = User::find(1300012);
    Auth::login($user);
});

//About
Route::prefix('about')->group(function () {
    Route::get('/', function() { return redirect(route('about.who-we-are'), 301); })->name('about.index');
    Route::view('/who-we-are', 'about.who-we-are')->name('about.who-we-are');
    Route::view('/core', 'about.about-core')->name('about.core');
    Route::get('/staff', 'Users\StaffListController@index')->name('staff');
});

//Authentication
Route::prefix('auth')->group(function () {
    Route::get('/sso/login', function () { return redirect(route('auth.connect.login'), 301); })->middleware('guest')->name('auth.sso.login');
    Route::get('/connect/login', 'Auth\AuthController@connectLogin')->middleware('guest')->name('auth.connect.login');
    Route::get('/connect/validate', 'Auth\AuthController@validateConnectLogin')->middleware('guest');
    Route::get('/logout', 'Auth\AuthController@logout')->middleware('auth')->name('auth.logout');
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
    Route::post('/privacyaccept', 'Community\MyCzqoController@acceptPrivacyPolicy')->name('privacyaccept');
    Route::get('/privacydeny', 'Community\MyCzqoController@denyPrivacyPolicy');
    Route::view('/my/accept-privacy-policy', 'my.accept-privacy-policy')->name('accept-privacy-policy');

    //Dashboard/MyCZQO
    Route::get('/dashboard', function() { return redirect(route('my.index'), 301); });
    Route::get('/my', 'PrimaryViewsController@dashboard')->name('my.index');

    //GDPR
    Route::get('/my/data', 'Users\DataController@index')->name('me.data');
    Route::post('/my/data/export/all', 'Users\DataController@exportAllData')->name('me.data.export.all');

    //Restricted role prohibitation
    Route::group(['middleware' => 'restricted'], function () {

        //Events
        Route::post('/dashboard/events/controllerapplications/ajax', 'Events\EventController@controllerApplicationAjaxSubmit')->name('events.controllerapplication.ajax');

        //Avatars/display name
        Route::post('/users/changeavatar', 'Community\MyCzqoController@changeAvatarCustomImage')->name('users.changeavatar');
        Route::get('/users/changeavatar/discord', 'Community\MyCzqoController@changeAvatarDiscord')->name('users.changeavatar.discord');
        Route::get('/users/changeavatar/initials', 'Community\MyCzqoController@changeAvatarInitials')->name('users.resetavatar');
        Route::post('/users/changedisplayname', 'Community\MyCzqoController@changeDisplayName')->name('users.changedisplayname');

        //CTP
        //Route::post('/dashboard/ctp/signup/post', 'DashboardController@ctpSignUp')->name('ctp.signup.post');

        //Notification
        Route::get('/notification/{id}', 'Users\NotificationRedirectController@notificationRedirect')->name('notification.redirect');
        Route::get('/notificationclear', 'Users\NotificationRedirectController@clearAll');


        //Feedback
        Route::get('/feedback', 'Feedback\FeedbackController@create')->name('feedback.create');
        Route::post('/feedback', 'Feedback\FeedbackController@createPost')->name('feedback.create.post');

        //Support
        Route::prefix('support')->group(function () {
            //Support home
            Route::get('/', 'Support\TicketsController@index')->name('support.index');
        });

        //Email prefs
        Route::get('/dashboard/emailpref', 'Users\DataController@emailPref')->name('dashboard.emailpref');
        Route::get('/dashboard/emailpref/subscribe', 'Users\DataController@subscribeEmails');
        Route::get('/dashboard/emailpref/unsubscribe', 'Users\DataController@unsubscribeEmails');

        //"My"
        Route::post('/me/editbiography', 'Community\MyCzqoController@saveBiography')->name('me.editbio');
        Route::get('/me/discord/unlink', 'Community\DiscordController@unlinkDiscord')->name('me.discord.unlink');
        Route::get('/me/discord/link/callback/{param?}', 'Community\DiscordController@linkCallbackDiscord')->name('me.discord.link.callback');
        Route::get('/me/discord/link/{param?}', 'Community\DiscordController@linkRedirectDiscord')->name('me.discord.link');
        Route::get('/me/discord/server/join', 'Community\DiscordController@joinRedirectDiscord')->name('me.discord.join');
        Route::get('/me/discord/server/join/callback', 'Community\DiscordController@joinCallbackDiscord');
        Route::get('/my/preferences', 'Community\MyCzqoController@preferences')->name('my.preferences');
        Route::post('/my/preferences', 'Community\MyCzqoController@preferencesPost')->name('my.preferences.post');

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

            //Portal
            Route::name('training.portal.')->group(function () {
                Route::get('portal', 'Training\TrainingPortalController@index')->name('index');
                Route::get('portal/help-policies', 'Training\TrainingPortalController@helpPolicies')->name('help-policies');
                //Training availability
                Route::get('portal/availability', 'Training\TrainingPortalController@viewAvailability')->name('availability');
                Route::post('portal/availability', 'Training\TrainingPortalController@submitAvailabilityPost')->name('availability.submit.post');
                //Progress
                Route::get('portal/progress', 'Training\TrainingPortalController@yourProgress')->name('progress');
                //Instructor
                Route::get('portal/your-instructor', 'Training\TrainingPortalController@yourInstructor')->name('your-instructor');
                //Training notes
                Route::get('portal/training-notes', 'Training\TrainingPortalController@yourTrainingNotes')->name('training-notes');
                //Actions
                Route::get('portal/actions', 'Training\TrainingPortalController@actions')->name('actions');
            });
        });

        //Support
        Route::prefix('support')->name('support.')->group(function () {
            Route::get('/', 'Support\TicketsController@index')->name('index');
        });


        Route::group(['middleware' => 'can:view events'], function () {
            //Events
            Route::get('/admin/events', 'Events\EventController@adminIndex')->name('events.admin.index');
            Route::get('/admin/events/create', 'Events\EventController@adminCreateEvent')->name('events.admin.create')->middleware('can:create event');
            Route::post('/admin/events/create', 'Events\EventController@adminCreateEventPost')->name('events.admin.create.post')->middleware('can:create event');
            Route::post('/admin/events/{slug}/edit', 'Events\EventController@adminEditEventPost')->name('events.admin.edit.post')->middleware('can:edit event');
            Route::post('/admin/events/{slug}/update/create', 'Events\EventController@adminCreateUpdatePost')->name('events.admin.update.post')->middleware('can:edit event');
            Route::get('/admin/events/{slug}', 'Events\EventController@adminViewEvent')->name('events.admin.view');
            Route::get('/admin/events/{slug}/delete', 'Events\EventController@adminDeleteEvent')->name('events.admin.delete')->middleware('can:delete event');
            Route::get('/admin/events/{slug}/controllerapps/{cid}/delete', 'Events\EventController@adminDeleteControllerApp')->name('events.admin.controllerapps.delete')->middleware('can:edit event');
            Route::get('/admin/events/{slug}/updates/{id}/delete', 'Events\EventController@adminDeleteUpdate')->name('events.admin.update.delete')->middleware('can:edit event');
        });

        //Admin
        Route::prefix('admin')->group(function () {

            //Settings
            Route::prefix('settings')->group(function () {
                Route::group(['middleware' => ['permission:edit settings']], function () {
                    Route::get('/', 'Settings\SettingsController@index')->name('settings.index');
                    Route::get('/site-information', 'Settings\SettingsController@siteInformation')->name('settings.siteinformation');
                    Route::post('/site-information', 'Settings\SettingsController@saveSiteInformation')->name('settings.siteinformation.post');
                    Route::get('/emails', 'Settings\SettingsController@emails')->name('settings.emails');
                    Route::post('/emails', 'Settings\SettingsController@saveEmails')->name('settings.emails.post');
                    Route::get('/activity-log', 'Settings\SettingsController@activityLog')->name('settings.activitylog');
                    Route::get('/rotation-images', 'Settings\SettingsController@rotationImages')->name('settings.rotationimages');
                    Route::get('/rotation-images/delete/{image_id}', 'Settings\SettingsController@deleteRotationImage')->name('settings.rotationimages.deleteimg');
                    Route::post('/rotation-images/uploadimg', 'Settings\SettingsController@uploadRotationImage')->name('settings.rotationimages.uploadimg');
                    Route::get('/staff', 'Settings\StaffController@editStaff')->name('settings.staff');
                });
            });

            //Training
            Route::prefix('training')->group(function () {
                Route::name('training.admin.')->group(function () {
                    Route::get('/', 'Training\TrainingAdminController@dashboard')->name('dashboard')->middleware('role:Instructor|Senior Staff|Administrator');

                    //Roster
                    Route::get('/roster', 'Roster\RosterController@admin')->name('roster')->middleware('can:view roster admin');
                    Route::post('/roster/add', 'Roster\RosterController@addRosterMemberPost')->name('roster.add')->middleware('can:edit roster');;
                    Route::get('/roster/export', 'Roster\RosterController@exportRoster')->name('roster.export')->middleware('can:view roster admin');
                    Route::get('/roster/home-page-new-controllers', 'Roster\RosterController@homePageNewControllers')->name('roster.home-page-new-controllers')->middleware('can:edit roster');
                    Route::post('/roster/home-page-new-controllers/remove', 'Roster\RosterController@homePageNewControllersRemoveEntry')->name('roster.home-page-new-controllers.remove')->middleware('can:edit roster');
                    Route::post('/roster/home-page-new-controllers/add', 'Roster\RosterController@homePageNewControllersAddEntry')->name('roster.home-page-new-controllers.add')->middleware('can:edit roster');
                    Route::get('/roster/{cid}', 'Roster\RosterController@viewRosterMember')->name('roster.viewcontroller')->middleware('can:view roster admin');;
                    Route::get('/roster/{cid}/delete', 'Roster\RosterController@removeRosterMember')->name('roster.removecontroller')->middleware('can:edit roster');;
                    Route::post('/roster/{cid}/edit', 'Roster\RosterController@editRosterMemberPost')->name('roster.editcontroller')->middleware('can:edit roster');


                    //Solo certifications
                    Route::get('/solocertifications', 'Training\SoloCertificationsController@admin')->name('solocertifications')->middleware('can:view roster admin');
                    Route::post('/solocertifications/add', 'Training\SoloCertificationsController@addSoloCertificationPost')->name('solocertifications.add')->middleware('can:edit roster');

                    //Applications
                    Route::get('/applications', 'Training\ApplicationsController@admin')->name('applications')->middleware('can:view applications');
                    Route::get('/applications/processed', 'Training\ApplicationsController@adminProcessedApplications')->name('applications.processed')->middleware('can:view applications');
                    Route::get('/applications/withdrawn', 'Training\ApplicationsController@adminWithdrawnApplications')->name('applications.withdrawn')->middleware('can:view applications');
                    Route::post('applications/comment/post', 'Training\ApplicationsController@adminCommentPost')->name('applications.comment.post')->middleware('can:interact with applications');
                    Route::get('/applications/{reference_id}', 'Training\ApplicationsController@adminViewApplication')->name('applications.view')->middleware('can:view applications');
                    Route::get('/applications/{reference_id}/accept', 'Training\ApplicationsController@adminAcceptApplication')->name('applications.accept')->middleware('can:interact with applications');
                    Route::get('/applications/{reference_id}/reject', 'Training\ApplicationsController@adminRejectApplication')->name('applications.reject')->middleware('can:interact with applications');

                    //Instructing
                    Route::prefix('instructing')->group(function () {
                        Route::group(['middleware' => 'can:view instructing admin'], function () {
                            //Calendar
                            Route::get('/calendar', 'Training\InstructingController@calendar')->name('instructing.calendar');

                            //Boards
                            Route::get('/board', 'Training\InstructingController@board')->name('instructing.board');

                            //Your Students/Sessions
                            Route::get('/your-students', 'Training\InstructingController@yourStudents')->name('instructing.your-students')->middleware('role:Instructor');
                            Route::get('/your-upcoming-sessions', 'Training\SessionsController@yourUpcomingSessions')->name('instructing.your-upcoming-sessions')->middleware('role:Instructor');

                            //Instructors
                            Route::get('/instructors', 'Training\InstructingController@instructors')->name('instructing.instructors');
                            Route::post('/instructors/add', 'Training\InstructingController@addInstructor')->name('instructing.instructors.add')->middleware('can:edit instructors');
                            Route::post('/instructors/{cid}/edit', 'Training\InstructingController@editInstructor')->name('instructing.instructors.edit')->middleware('can:edit instructors');
                            Route::get('/instructors/{cid}', 'Training\InstructingController@viewInstructor')->name('instructing.instructors.view');
                            Route::get('/instructors/{cid}/remove', 'Training\InstructingController@removeInstructor')->name('instructing.instructors.remove')->middleware('can:edit instructors');

                            //Students
                            Route::get('/students', 'Training\InstructingController@students')->name('instructing.students');
                            Route::post('/students/add', 'Training\InstructingController@addStudent')->name('instructing.students.add')->middleware('can:edit students');
                            Route::get('/students/{cid}', 'Training\InstructingController@viewStudent')->name('instructing.students.view');
                            Route::get('/students/{cid}/records/training-notes', 'Training\RecordsController@studentTrainingNotes')->name('instructing.students.records.training-notes');
                            Route::get('/students/{cid}/remove', 'Training\InstructingController@removeStudent')->name('instructing.students.remove')->middleware('can:edit students');

                            //Training notes
                            Route::get('/students/{cid}/records/training-notes/create', 'Training\RecordsController@createStudentTrainingNote')->name('instructing.students.records.training-notes.create')->middleware('can:edit training records');
                            Route::post('/students/{cid}/records/training-notes/create', 'Training\RecordsController@createStudentTrainingNotePost')->name('instructing.students.records.training-notes.create.post')->middleware('can:edit training records');
                            Route::get('/students/{cid}/records/training-notes/{training_note_id}/delete', 'Training\RecordsController@deleteStudentTrainingNote')->name('instructing.students.records.training-notes.delete')->middleware('can:edit training records');

                            //Assign student to instructor
                            Route::post('/students/{cid}/assign/instructor', 'Training\InstructingController@assignStudentToInstructor')->name('instructing.students.assign.instructor')->middleware('can:assign instructor to student');
                            Route::get('/students/{cid}/drop/instructor', 'Training\InstructingController@dropStudentFromInstructor')->name('instructing.students.drop.instructor')->middleware('can:assign instructor to student');

                            //Student status labels
                            Route::get('/students/{cid}/drop/label/{label_link_id}', 'Training\InstructingController@dropStatusLabelFromStudent')->name('instructing.students.drop.label')->middleware('role:Instructor');
                            Route::post('/students/{cid}/assign/label', 'Training\InstructingController@assignStatusLabelToStudent')->name('instructing.student.assign.label')->middleware('role:Instructor');

                            //Student recommendation requests
                            Route::get('/students/{cid}/request/recommend/solocert', 'Training\InstructingController@recommendSoloCertification')->name('instructing.students.request.recommend.solocert')->middleware('role:Instructor');
                            Route::get('/students/{cid}/request/recommend/assessment', 'Training\InstructingController@recommendAssessment')->name('instructing.students.request.recommend.assessment')->middleware('role:Instructor');

                            //Training sessions
                            Route::get('/training-sessions', 'Training\SessionsController@trainingSessionsIndex')->name('instructing.training-sessions');
                            Route::get('/training-sessions/{id}', 'Training\SessionsController@viewTrainingSession')->name('instructing.training-sessions.view');
                            Route::post('/training-sessions/{id}/edit/time', 'Training\SessionsController@editTrainingSessionTime')->name('instructing.training-sessions.edit.time')->middleware('can:edit training sessions');
                            Route::post('/training-sessions/{id}/edit/instructor', 'Training\SessionsController@reassignTrainingSessionInstructor')->name('instructing.training-sessions.edit.instructor')->middleware('can:edit training sessions');

                            //OTS sessions
                            Route::get('/ots-sessions', 'Training\SessionsController@otsSessionsIndex')->name('instructing.ots-sessions');
                        });
                    });
                });
            });

            //News
            Route::prefix('news')->group(function () {
                Route::group(['middleware' => 'can:view articles'], function () {
                    Route::get('/', 'News\NewsController@index')->name('news.index');
                    Route::get('/article/create', 'News\NewsController@createArticle')->name('news.articles.create')->middleware('can:create articles');
                    Route::post('/article/create', 'News\NewsController@postArticle')->name('news.articles.create.post')->middleware('can:create articles');
                    Route::get('/article/{slug}', 'News\NewsController@viewArticle')->name('news.articles.view');
                    Route::get('/announcement/create', 'News\NewsController@createAnnouncement')->name('news.announcements.create')->middleware('can:send announcements');
                    Route::post('/announcement/create', 'News\NewsController@createAnnouncementPost')->name('news.announcements.create.post')->middleware('can:send announcements');
                    Route::get('/announcement/{slug}', 'News\NewsController@viewAnnouncement')->name('news.announcements.view');
                });
            });

            //Publications
            Route::prefix('publications')->group(function () {
                Route::group(['middleware' => ['permission:edit policies']], function () {
                    Route::get('/policies', 'Publications\PublicationsController@adminPolicies')->name('publications.policies');
                    Route::post('/policies/create', 'Publications\PublicationsController@createPolicyPost')->name('publications.policies.create.post')->middleware('can:edit policies');
                    Route::post('/policies/{id}/edit', 'Publications\PublicationsController@editPolicyPost')->name('publications.policies.edit.post')->middleware('can:edit policies');
                    Route::get('/policies/{id}/delete', 'Publications\PublicationsController@deletePolicy')->name('publications.policies.delete')->middleware('can:edit policies');
                });

                Route::group(['middleware' => ['permission:edit atc resources']], function () {
                    Route::get('/atc-resources', 'Publications\PublicationsController@adminAtcResources')->name('publications.atc-resources');
                    Route::post('/atc-resources/create', 'Publications\PublicationsController@createAtcResourcePost')->name('publications.atc-resources.create.post')->middleware('can:edit atc resources');
                    Route::post('/atc-resources/{id}/edit', 'Publications\PublicationsController@editAtcResourcePost')->name('publications.atc-resources.edit.post')->middleware('can:edit atc resources');
                    Route::get('/atc-resources/{id}/delete', 'Publications\PublicationsController@deleteAtcResource')->name('publications.atc-resources.delete')->middleware('can:edit atc resources');
                });

                Route::group(['middleware' => ['permission:edit atc resources']], function () {
                    Route::get('/custom-pages', 'Publications\CustomPagesController@admin')->name('publications.custom-pages');
                    Route::get('/custom-pages/{slug}', 'Publications\CustomPagesController@adminViewPage')->name('publications.custom-pages.view');
                });
            });

            //Network
            Route::prefix('network')->group(function () {
                Route::group(['middleware' => ['permission:view network data']], function () {
                    Route::get('/', 'Network\NetworkController@index')->name('network.index');
                    Route::get('/monitored-positions', 'Network\NetworkController@monitoredPositionsIndex')->name('network.monitoredpositions.index');
                    Route::get('/monitored-positions/{position}', 'Network\NetworkController@viewMonitoredPosition')->name('network.monitoredpositions.view');
                    Route::post('/monitored-positions/create', 'Network\NetworkController@createMonitoredPosition')->name('network.monitoredpositions.create')->middleware('edit monitored positions');
                });
            });

            //Community
            Route::prefix('community')->group(function () {
                //User Management
                Route::group(['middleware' => ['permission:view users']], function () {
                    Route::get('/users', 'Community\UsersController@index')->name('community.users.index');
                    Route::get('/users/{id}', 'Community\UsersController@viewUser')->name('community.users.view');
                    Route::get('/users/{id}/reset/avatar', 'Community\UsersController@resetUserAvatar')->name('community.users.reset.avatar')->middleware('can:edit user data');
                    Route::post('/users/{id}/assign/role', 'Community\UsersController@assignUserRole')->name('community.users.assign.role')->middleware('can:edit user data');
                    Route::post('/users/{id}/assign/permission', 'Community\UsersController@assignUserPermission')->name('community.users.assign.permission')->middleware('can:edit user data');
                    Route::delete('/users/{id}/remove/role', 'Community\UsersController@removeUserRole')->name('community.users.remove.role')->middleware('can:edit user data');
                    Route::delete('/users/{id}/remove/permission', 'Community\UsersController@removeUserPermission')->name('community.users.remove.permission')->middleware('can:edit user data');
                });
            });

        });

    });

});

//Custom pages
Route::get('/{page_slug}', 'Publications\CustomPagesController@viewPage')->name('publications.custompages.view');
Route::post('/{page_slug}/response-submit', 'Publications\CustomPagesController@submitResponse')->name('publications.custompages.response-submit');
