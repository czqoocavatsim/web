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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\Users\DataController;
use App\Http\Controllers\Events\EventController;
use App\Http\Controllers\PrimaryViewsController;
use App\Http\Controllers\Roster\RosterController;
use App\Http\Controllers\Settings\StaffController;
use App\Http\Controllers\Community\UsersController;
use App\Http\Controllers\Network\NetworkController;
use App\Http\Controllers\Users\StaffListController;
use App\Http\Controllers\Community\MyCzqoController;
use App\Http\Controllers\Training\RecordsController;
use App\Http\Controllers\Community\DiscordController;
use App\Http\Controllers\Feedback\FeedbackController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Training\SessionsController;
use App\Http\Controllers\Training\InstructingController;
use App\Http\Controllers\Training\ApplicationsController;
use App\Http\Controllers\Training\TrainingAdminController;
use App\Http\Controllers\Training\TrainingPortalController;
use App\Http\Controllers\Publications\CustomPagesController;
use App\Http\Controllers\Publications\PublicationsController;
use App\Http\Controllers\TrainingCalendar\TrainingController;
use App\Http\Controllers\Training\SoloCertificationsController;

Route::get('/', [PrimaryViewsController::class, 'home'])->name('index');
Route::get('/map', [PrimaryViewsController::class, 'map'])->name('map');
Route::get('/roster', [RosterController::class, 'publicRoster'])->middleware('auth')->name('roster.public');
Route::get('/roster/solo-certs', [SoloCertificationsController::class, 'public'])->middleware('auth')->name('solocertifications.public');
Route::get('/staff', fn() => redirect(route('staff'), 301));
Route::get('/atc/resources', [PublicationsController::class, 'atcResources'])->name('atcresources.index');
Route::view('/pilots', 'pilots.index');
Route::view('/pilots/oceanic-clearance', 'pilots.oceanic-clearance')->name('pilots.oceanic-clearance');
Route::view('/pilots/position-report', 'pilots.position-report')->name('pilots.position-report');
Route::view('/pilots/tracks', 'pilots.tracks')->name('pilots.tracks');
Route::view('/pilots/tracks/event', 'pilots.event-tracks')->name('pilots.event-tracks');
Route::view('/pilots/tracks/concorde', 'pilots.concorde-tracks')->name('pilots.concorde-tracks');
Route::get('/policies', [PublicationsController::class, 'policies'])->name('policies');
Route::get('/privacy', fn() => redirect(route('policies'), 301))->name('privacy');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'viewEvent'])->name('events.view');
Route::view('/branding', 'about.branding')->name('branding');

// About
Route::prefix('about')->group(function () {
    Route::get('/', fn() => redirect(route('about.who-we-are'), 301))->name('about.index');
    Route::view('/who-we-are', 'about.who-we-are')->name('about.who-we-are');
    Route::view('/core', 'about.about-core')->name('about.core');
    Route::get('/staff', [StaffListController::class, 'index'])->name('staff')->middleware('auth');
});

// Authentication
Route::prefix('auth')->group(function () {
    Route::get('/sso/login', fn() => redirect(route('auth.connect.login'), 301))->middleware('guest')->name('auth.sso.login');
    Route::get('/connect/login', [AuthController::class, 'connectLogin'])->middleware('guest')->name('auth.connect.login');
    Route::get('/connect/validate', [AuthController::class, 'validateConnectLogin'])->middleware('guest');
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');
});

// Discord shortcut
Route::get('/discord', [DiscordController::class, 'joinShortcut']);

// Public news articles
Route::get('/news/{id}', [NewsController::class, 'viewArticlePublic'])->name('news.articlepublic')->where('id', '[0-9]+');
Route::get('/news/{slug}', [NewsController::class, 'viewArticlePublic'])->name('news.articlepublic');
Route::get('/news', [NewsController::class, 'viewAllPublic'])->name('news');


//Base level authentication
Route::group(['middleware' => 'auth'], function () {

    // Privacy accept
    Route::post('/privacyaccept', [MyCzqoController::class, 'acceptPrivacyPolicy'])->name('privacyaccept');
    Route::get('/privacydeny', [MyCzqoController::class, 'denyPrivacyPolicy']);
    Route::view('/my/accept-privacy-policy', 'my.accept-privacy-policy')->name('accept-privacy-policy');

    // Dashboard/MyCZQO
    Route::get('/dashboard', fn() => redirect(route('my.index'), 301));
    Route::get('/my', [PrimaryViewsController::class, 'dashboard'])->name('my.index');

    // GDPR
    Route::get('/my/data', [DataController::class, 'index'])->name('me.data');
    Route::post('/my/data/export/all', [DataController::class, 'exportAllData'])->name('me.data.export.all');


    //Restricted role prohibitation
    Route::group(['middleware' => 'restricted'], function () {

        // Events
        Route::post('/dashboard/events/controllerapplications/ajax', [EventController::class, 'controllerApplicationAjaxSubmit'])->name('events.controllerapplication.ajax');

        // Avatars/display name
        Route::post('/users/changeavatar', [MyCzqoController::class, 'changeAvatarCustomImage'])->name('users.changeavatar');
        Route::get('/users/changeavatar/discord', [MyCzqoController::class, 'changeAvatarDiscord'])->name('users.changeavatar.discord');
        Route::get('/users/changeavatar/initials', [MyCzqoController::class, 'changeAvatarInitials'])->name('users.resetavatar');
        Route::post('/users/changedisplayname', [MyCzqoController::class, 'changeDisplayName'])->name('users.changedisplayname');

        // Notification
        // Route::get('/notification/{id}', [NotificationRedirectController::class, 'notificationRedirect'])->name('notification.redirect');
        // Route::get('/notificationclear', [NotificationRedirectController::class, 'clearAll']);

        // Email prefs
        Route::get('/dashboard/emailpref', [DataController::class, 'emailPref'])->name('dashboard.emailpref');
        Route::get('/dashboard/emailpref/subscribe', [DataController::class, 'subscribeEmails']);
        Route::get('/dashboard/emailpref/unsubscribe', [DataController::class, 'unsubscribeEmails']);

        //"My"
        Route::post('/my/editbiography', [MyCzqoController::class, 'saveBiography'])->name('me.editbio');
        Route::get('/my/discord/unlink', [DiscordController::class, 'unlinkDiscord'])->name('me.discord.unlink');
        Route::get('/my/discord/link/callback', [DiscordController::class, 'linkCallbackDiscord'])->name('me.discord.link.callback');
        Route::get('/my/discord/link', [DiscordController::class, 'linkRedirectDiscord'])->name('me.discord.link');
        Route::get('/my/discord/server/join', [DiscordController::class, 'joinRedirectDiscord'])->name('me.discord.join');
        Route::get('/my/discord/server/join/callback', [DiscordController::class, 'joinCallbackDiscord']);
        Route::get('/my/preferences', [MyCzqoController::class, 'preferences'])->name('my.preferences');
        Route::post('/my/preferences', [MyCzqoController::class, 'preferencesPost'])->name('my.preferences.post');


        //Feedback
        Route::prefix('my/feedback')->group(function () {
            Route::get('/new', [FeedbackController::class, 'newFeedback'])->name('my.feedback.new');
            Route::get('/new/{type_slug}', [FeedbackController::class, 'newFeedbackWrite'])->name('my.feedback.new.write');
            Route::post('/new/{type_slug}', [FeedbackController::class, 'newFeedbackWritePost'])->name('my.feedback.new.write.post');
            Route::get('/', [FeedbackController::class, 'myFeedback'])->name('my.feedback');
            Route::get('/{slug}', [FeedbackController::class, 'viewSubmission'])->name('my.feedback.submission');
        });

        //Bookings Calendar
        Route::get('/atc/training-calendar', [TrainingController::class, 'index'])->name('trainingcalendar.index');
        Route::get('/atc/training-calendar/training-sessions', [TrainingController::class, 'getTrainingSessions'])->name('trainingcalendar.trainingsessions');
        Route::get('/atc/training-calendar/ots-sessions', [TrainingController::class, 'getOtsSessions'])->name('trainingcalendar.otssessions');
        Route::get('/atc/training-calendar/training/training-sessions', [TrainingController::class, 'getTrainingSessionsAdmin'])->name('trainingcalendar.training.trainingsessions');
        Route::get('/atc/training-calendar/training/ots-sessions', [TrainingController::class, 'getOtsSessionsAdmin'])->name('trainingcalendar.training.otssessions');

        //Training
        Route::prefix('training')->group(function () {

            // Applications
            Route::get('applications', [ApplicationsController::class, 'showAll'])->name('training.applications.showall');
            Route::get('applications/apply', [ApplicationsController::class, 'apply'])->name('training.applications.apply');
            Route::post('applications/apply', [ApplicationsController::class, 'applyPost'])->name('training.applications.apply.post');
            Route::post('applications/withdraw', [ApplicationsController::class, 'withdraw'])->name('training.applications.withdraw');
            Route::post('applications/comment/post', [ApplicationsController::class, 'commentPost'])->name('training.applications.comment.post');
            Route::get('applications/{reference_id}', [ApplicationsController::class, 'show'])->name('training.applications.show');
            Route::get('applications/{reference_id}/updates', [ApplicationsController::class, 'showUpdates'])->name('training.applications.show.updates');

            // Portal
            Route::name('training.portal.')->group(function () {
                Route::get('portal', [TrainingPortalController::class, 'index'])->name('index');
                Route::get('portal/help-policies', [TrainingPortalController::class, 'helpPolicies'])->name('help-policies');
                //Acknowledgements
                Route::view('portal/acknowledgements', 'training.portal.acknowledgements')->name('controller-acknowledgements');
                Route::post('portal/acknowledgements/read/{announcement}', [TrainingPortalController::class, 'readAcknowledgement'])->name('controller-acknowledgements.read');
                // Training availability
                Route::get('portal/availability', [TrainingPortalController::class, 'viewAvailability'])->name('availability');
                Route::post('portal/availability', [TrainingPortalController::class, 'submitAvailabilityPost'])->name('availability.submit.post');
                // Progress
                Route::get('portal/progress', [TrainingPortalController::class, 'yourProgress'])->name('progress');
                // Instructor
                Route::get('portal/your-instructor', [TrainingPortalController::class, 'yourInstructor'])->name('your-instructor');
                // Training notes
                Route::get('portal/training-notes', [TrainingPortalController::class, 'yourTrainingNotes'])->name('training-notes');
                // Actions
                Route::get('portal/actions', [TrainingPortalController::class, 'actions'])->name('actions');
                // Training sessions
                Route::get('portal/sessions', [TrainingPortalController::class, 'yourSessions'])->name('sessions');
                Route::get('portal/sessions/training/{id}', [TrainingPortalController::class, 'viewTrainingSession'])->name('sessions.view-training-session');
                Route::get('portal/sessions/ots/{id}', [TrainingPortalController::class, 'viewOtsSession'])->name('sessions.view-ots-session');
            });

        });

        //Admin
        Route::group(['prefix' => 'admin'], function () {

            Route::view('/', 'admin.index')->name('admin.index');

            //Events
            Route::group(['middleware' => 'can:view events', 'prefix' => 'events'], function () {
                Route::get('/', [EventController::class, 'adminIndex'])->name('events.admin.index');
                Route::get('/create', [EventController::class, 'adminCreateEvent'])->name('events.admin.create')->middleware('can:create event');
                Route::post('/create', [EventController::class, 'adminCreateEventPost'])->name('events.admin.create.post')->middleware('can:create event');
                Route::post('/{slug}/edit', [EventController::class, 'adminEditEventPost'])->name('events.admin.edit.post')->middleware('can:edit event');
                Route::post('/{slug}/update/create', [EventController::class, 'adminCreateUpdatePost'])->name('events.admin.update.post')->middleware('can:edit event');
                Route::get('/{slug}', [EventController::class, 'adminViewEvent'])->name('events.admin.view');
                Route::get('/{slug}/delete', [EventController::class, 'adminDeleteEvent'])->name('events.admin.delete')->middleware('can:delete event');
                Route::get('/{slug}/controllerapps/{cid}/delete', [EventController::class, 'adminDeleteControllerApp'])->name('events.admin.controllerapps.delete')->middleware('can:edit event');
                Route::get('/{slug}/updates/{id}/delete', [EventController::class, 'adminDeleteUpdate'])->name('events.admin.update.delete')->middleware('can:edit event');
            });

            // //Settings
            Route::group(['middleware' => 'permission:edit settings', 'prefix' => 'settings'], function () {
                Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
                Route::get('/site-information', [SettingsController::class, 'siteInformation'])->name('settings.siteinformation');
                Route::post('/site-information', [SettingsController::class, 'saveSiteInformation'])->name('settings.siteinformation.post');
                Route::get('/emails', [SettingsController::class, 'emails'])->name('settings.emails');
                Route::post('/emails', [SettingsController::class, 'saveEmails'])->name('settings.emails.post');
                Route::get('/activity-log', [SettingsController::class, 'activityLog'])->name('settings.activitylog');
                Route::get('/rotation-images', [SettingsController::class, 'rotationImages'])->name('settings.rotationimages');
                Route::get('/rotation-images/delete/{image_id}', [SettingsController::class, 'deleteRotationImage'])->name('settings.rotationimages.deleteimg');
                Route::post('/rotation-images/uploadimg', [SettingsController::class, 'uploadRotationImage'])->name('settings.rotationimages.uploadimg');

                Route::get('/staff', [StaffController::class, 'index'])->name('settings.staff');
                Route::post('/staff/store', [StaffController::class, 'store'])->name('settings.staff.store');
                Route::post('/staff/{staffMember:id}/update', [StaffController::class, 'update'])->name('settings.staff.update');
                Route::post('/staff/{staffMember:id}/delete', [StaffController::class, 'delete'])->name('settings.staff.delete');
            });

            //Training
            Route::prefix('training')->group(function () {
                Route::name('training.admin.')->group(function () {

                    Route::get('/', [TrainingAdminController::class, 'dashboard'])->name('dashboard')->middleware('role:Instructor|Senior Staff|Administrator');

                    //Roster
                    Route::get('/roster', [RosterController::class, 'admin'])->name('roster')->middleware('can:view roster admin');
                    Route::post('/roster/add', [RosterController::class, 'addRosterMemberPost'])->name('roster.add')->middleware('can:edit roster');
                    Route::get('/roster/export', [RosterController::class, 'exportRoster'])->name('roster.export')->middleware('can:view roster admin');
                    Route::get('/roster/home-page-new-controllers', [RosterController::class, 'homePageNewControllers'])->name('roster.home-page-new-controllers')->middleware('can:edit roster');
                    Route::post('/roster/home-page-new-controllers/remove', [RosterController::class, 'homePageNewControllersRemoveEntry'])->name('roster.home-page-new-controllers.remove')->middleware('can:edit roster');
                    Route::post('/roster/home-page-new-controllers/add', [RosterController::class, 'homePageNewControllersAddEntry'])->name('roster.home-page-new-controllers.add')->middleware('can:edit roster');
                    Route::get('/roster/{cid}', [RosterController::class, 'viewRosterMember'])->name('roster.viewcontroller')->middleware('can:view roster admin');
                    Route::get('/roster/{cid}/delete', [RosterController::class, 'removeRosterMember'])->name('roster.removecontroller')->middleware('can:edit roster');
                    Route::post('/roster/{cid}/edit', [RosterController::class, 'editRosterMemberPost'])->name('roster.editcontroller')->middleware('can:edit roster');

                    //Solo certifications
                    Route::get('/solocertifications', [SoloCertificationsController::class, 'admin'])->name('solocertifications')->middleware('can:view roster admin');
                    Route::post('/solocertifications/add', [SoloCertificationsController::class, 'addSoloCertificationPost'])->name('solocertifications.add')->middleware('can:edit roster');
                    Route::get('/solocertifications/{cert_id}/revoke', [SoloCertificationsController::class, 'revokeSoloCert'])->name('solocertifications.revoke')->middleware('can:edit roster');

                    //Controller Acknowledgements
                    Route::view('/acknowledgements', 'admin.training.acknowledgements.index')->name('acknowledgements');
                    Route::get('/acknowledgement/{announcement}', [RosterController::class, 'getAcknowledgement'])->name('acknowledgement.find');

                    //Applications
                    Route::get('/applications', [ApplicationsController::class, 'admin'])->name('applications')->middleware('can:view applications');
                    Route::get('/applications/processed', [ApplicationsController::class, 'adminProcessedApplications'])->name('applications.processed')->middleware('can:view applications');
                    Route::get('/applications/withdrawn', [ApplicationsController::class, 'adminWithdrawnApplications'])->name('applications.withdrawn')->middleware('can:view applications');
                    Route::post('applications/comment/post', [ApplicationsController::class, 'adminCommentPost'])->name('applications.comment.post')->middleware('can:interact with applications');
                    Route::get('/applications/{reference_id}', [ApplicationsController::class, 'adminViewApplication'])->name('applications.view')->middleware('can:view applications');
                    Route::get('/applications/{reference_id}/accept', [ApplicationsController::class, 'adminAcceptApplication'])->name('applications.accept')->middleware('can:interact with applications');
                    Route::get('/applications/{reference_id}/reject', [ApplicationsController::class, 'adminRejectApplication'])->name('applications.reject')->middleware('can:interact with applications');


                    //Instructing
                    Route::group(['middleware' => 'can:view instructing admin', 'prefix' => 'instructing'], function () {

                        //Calendar
                        Route::get('/calendar', [InstructingController::class, 'calendar'])->name('instructing.calendar');
                        //Board
                        Route::get('/board', [InstructingController::class, 'board'])->name('instructing.board');
                        //Your Students/Sessions
                        Route::get('/your-students', [InstructingController::class, 'yourStudents'])->name('instructing.your-students')->middleware('role:Instructor');
                        Route::get('/your-upcoming-sessions', [SessionsController::class, 'yourUpcomingSessions'])->name('instructing.your-upcoming-sessions')->middleware('role:Instructor');
                        //Instructors
                        Route::get('/instructors', [InstructingController::class, 'instructors'])->name('instructing.instructors');
                        Route::post('/instructors/add', [InstructingController::class, 'addInstructor'])->name('instructing.instructors.add')->middleware('can:edit instructors');
                        Route::post('/instructors/{cid}/edit', [InstructingController::class, 'editInstructor'])->name('instructing.instructors.edit')->middleware('can:edit instructors');
                        Route::get('/instructors/{cid}', [InstructingController::class, 'viewInstructor'])->name('instructing.instructors.view');
                        Route::get('/instructors/{cid}/remove', [InstructingController::class, 'removeInstructor'])->name('instructing.instructors.remove')->middleware('can:edit instructors');
                        //Students
                        Route::get('/students', [InstructingController::class, 'students'])->name('instructing.students');
                        Route::post('/students/add', [InstructingController::class, 'addStudent'])->name('instructing.students.add')->middleware('can:edit students');
                        Route::get('/students/{cid}', [InstructingController::class, 'viewStudent'])->name('instructing.students.view');
                        Route::get('/students/{cid}/records/training-notes', [RecordsController::class, 'studentTrainingNotes'])->name('instructing.students.records.training-notes');
                        Route::get('/students/{cid}/remove', [InstructingController::class, 'removeStudent'])->name('instructing.students.remove')->middleware('can:edit students');
                        //Training notes
                        Route::get('/students/{cid}/records/training-notes/create', [RecordsController::class, 'createStudentTrainingNote'])->name('instructing.students.records.training-notes.create')->middleware('can:edit training records');
                        Route::post('/students/{cid}/records/training-notes/create', [RecordsController::class, 'createStudentTrainingNotePost'])->name('instructing.students.records.training-notes.create.post')->middleware('can:edit training records');
                        Route::get('/students/{cid}/records/training-notes/{training_note_id}/delete', [RecordsController::class, 'deleteStudentTrainingNote'])->name('instructing.students.records.training-notes.delete')->middleware('can:edit training records');
                        Route::get('/students/{cid}/records/training-notes/{training_note_id}/edit', [RecordsController::class, 'editStudentTrainingNote'])->name('instructing.students.records.training-notes.edit')->middleware('can:edit training records');
                        Route::post('/students/{cid}/records/training-notes/{training_note_id}/edit', [RecordsController::class, 'editpostStudentTrainingNote'])->name('instructing.students.records.training-notes.post.edit')->middleware('can:edit training records');
                        //Assign student to instructor
                        Route::post('/students/{cid}/assign/instructor', [InstructingController::class, 'assignStudentToInstructor'])->name('instructing.students.assign.instructor')->middleware('can:assign instructor to student');
                        Route::get('/students/{cid}/drop/instructor', [InstructingController::class, 'dropStudentFromInstructor'])->name('instructing.students.drop.instructor')->middleware('can:assign instructor to student');
                        //Student status labels
                        Route::get('/students/{cid}/drop/label/{label_link_id}', [InstructingController::class, 'dropStatusLabelFromStudent'])->name('instructing.students.drop.label')->middleware('role:Instructor');
                        Route::post('/students/{cid}/assign/label', [InstructingController::class, 'assignStatusLabelToStudent'])->name('instructing.student.assign.label')->middleware('role:Instructor');
                        //Student recommendation requests
                        Route::get('/students/{cid}/request/recommend/solocert', [InstructingController::class, 'recommendSoloCertification'])->name('instructing.students.request.recommend.solocert')->middleware('role:Instructor');
                        Route::get('/students/{cid}/request/recommend/assessment', [InstructingController::class, 'recommendAssessment'])->name('instructing.students.request.recommend.assessment')->middleware('role:Instructor');
                        //Training sessions
                        Route::get('/training-sessions', [SessionsController::class, 'trainingSessionsIndex'])->name('instructing.training-sessions');
                        Route::post('/training-sessions/create', [SessionsController::class, 'createTrainingSession'])->name('instructing.training-sessions.create');
                        Route::post('/training-sessions/ajax/remarks', [SessionsController::class, 'saveTrainingSessionRemarks'])->name('instructing.training-sessions.ajax.remarks');
                        Route::get('/training-sessions/{id}', [SessionsController::class, 'viewTrainingSession'])->name('instructing.training-sessions.view');
                        Route::post('/training-sessions/{id}/edit/time', [SessionsController::class, 'editTrainingSessionTime'])->name('instructing.training-sessions.edit.time')->middleware('can:edit training sessions');
                        Route::post('/training-sessions/{id}/edit/instructor', [SessionsController::class, 'reassignTrainingSessionInstructor'])->name('instructing.training-sessions.edit.instructor')->middleware('can:edit training sessions');
                        Route::get('/training-sessions/{id}/cancel', [SessionsController::class, 'cancelTrainingSession'])->name('instructing.training-sessions.cancel')->middleware('can:edit training sessions');
                        Route::post('/training-sessions/{id}/edit/position', [SessionsController::class, 'assignTrainingSessionPosition'])->name('instructing.training-sessions.edit.position')->middleware('can:edit training sessions');
                        //OTS sessions
                        Route::get('/ots-sessions', [SessionsController::class, 'otsSessionsIndex'])->name('instructing.ots-sessions');
                        Route::post('/ots-sessions/create', [SessionsController::class, 'createOtsSession'])->name('instructing.ots-sessions.create');
                        Route::post('/ots-sessions/ajax/remarks', [SessionsController::class, 'saveOtsSessionRemarks'])->name('instructing.ots-sessions.ajax.remarks');
                        Route::get('/ots-sessions/{id}', [SessionsController::class, 'viewOtsSession'])->name('instructing.ots-sessions.view');
                        Route::post('/ots-sessions/{id}/edit/time', [SessionsController::class, 'editOtsSessionTime'])->name('instructing.ots-sessions.edit.time')->middleware('can:edit ots sessions');
                        Route::post('/ots-sessions/{id}/edit/instructor', [SessionsController::class, 'reassignOtsSessionInstructor'])->name('instructing.ots-sessions.edit.instructor')->middleware('can:edit ots sessions');
                        Route::get('/ots-sessions/{id}/cancel', [SessionsController::class, 'cancelOtsSession'])->name('instructing.ots-sessions.cancel')->middleware('can:edit ots sessions');
                        Route::post('/ots-sessions/{id}/edit/position', [SessionsController::class, 'assignOtsSessionPosition'])->name('instructing.ots-sessions.edit.position')->middleware('can:edit ots sessions');
                        Route::post('/ots-sessions/{id}/result/pass', [SessionsController::class, 'markOtsSessionAsPassed'])->name('instructing.ots-sessions.result.pass')->middleware('can:edit ots sessions');
                        Route::post('/ots-sessions/{id}/result/fail', [SessionsController::class, 'markOtsSessionAsFailed'])->name('instructing.ots-sessions.result.fail')->middleware('can:edit ots sessions');
                    });
                });
            });

            //News
            Route::group(['middleware' => 'can:view articles', 'prefix' => 'news'], function () {
                Route::get('/', [NewsController::class, 'index'])->name('news.index');
                Route::get('/article/create', [NewsController::class, 'createArticle'])->name('news.articles.create')->middleware('can:create articles');
                Route::post('/article/create', [NewsController::class, 'postArticle'])->name('news.articles.create.post')->middleware('can:create articles');
                Route::post('/article/{slug}/update/create', [NewsController::class, 'adminEditNewsArticle'])->name('news.article.update.post')->middleware('can:edit articles');
                Route::get('/article/{slug}', [NewsController::class, 'viewArticle'])->name('news.articles.view');
                Route::get('/announcement/create', [NewsController::class, 'createAnnouncement'])->name('news.announcements.create')->middleware('can:send announcements');
                Route::post('/announcement/create', [NewsController::class, 'createAnnouncementPost'])->name('news.announcements.create.post')->middleware('can:send announcements');
                Route::get('/announcement/{slug}', [NewsController::class, 'viewAnnouncement'])->name('news.announcements.view');
            });

            //Publications
            Route::prefix('publications')->group(function () {
                Route::group(['middleware' => ['permission:edit policies']], function () {
                    Route::get('/policies', [PublicationsController::class, 'adminPolicies'])->name('publications.policies');
                    Route::post('/policies/create', [PublicationsController::class, 'createPolicyPost'])->name('publications.policies.create.post')->middleware('can:edit policies');
                    Route::post('/policies/{id}/edit', [PublicationsController::class, 'editPolicyPost'])->name('publications.policies.edit.post')->middleware('can:edit policies');
                    Route::get('/policies/{id}/delete', [PublicationsController::class, 'deletePolicy'])->name('publications.policies.delete')->middleware('can:edit policies');
                });

                Route::group(['middleware' => ['permission:edit atc resources']], function () {
                    Route::get('/atc-resources', [PublicationsController::class, 'adminAtcResources'])->name('publications.atc-resources');
                    Route::post('/atc-resources/create', [PublicationsController::class, 'createAtcResourcePost'])->name('publications.atc-resources.create.post')->middleware('can:edit atc resources');
                    Route::post('/atc-resources/{id}/edit', [PublicationsController::class, 'editAtcResourcePost'])->name('publications.atc-resources.edit.post')->middleware('can:edit atc resources');
                    Route::get('/atc-resources/{id}/delete', [PublicationsController::class, 'deleteAtcResource'])->name('publications.atc-resources.delete')->middleware('can:edit atc resources');
                });

                Route::group(['middleware' => ['permission:edit atc resources']], function () {
                    Route::get('/custom-pages', [CustomPagesController::class, 'admin'])->name('publications.custom-pages');
                    Route::get('/custom-pages/create', [CustomPagesController::class, 'adminCreatePage'])->name('publications.custom-pages.create');
                    Route::post('/custom-pages/create', [CustomPagesController::class, 'adminPostCreatePage'])->name('publications.custom-pages.post.create');
                    Route::get('/custom-pages/{id}/edit', [CustomPagesController::class, 'adminEditPage'])->name('publications.custom-pages.edit');
                    Route::post('/custom-pages/{id}/edit/post', [CustomPagesController::class, 'adminEditPagePost'])->name('publications.custom-pages.post.edit');
                    Route::get('/custom-pages/{id}/delete', [CustomPagesController::class, 'deleteCustomPage'])->name('publications.custom-pages.delete');
                });
            });

            // Network
            Route::group(['middleware' => ['permission:view network data'], 'prefix' => 'network'], function () {
                Route::get('/', [NetworkController::class, 'index'])->name('network.index');
                Route::get('/monitored-positions', [NetworkController::class, 'monitoredPositionsIndex'])->name('network.monitoredpositions.index');
                Route::get('/monitored-positions/{position}', [NetworkController::class, 'viewMonitoredPosition'])->name('network.monitoredpositions.view');
                Route::post('/monitored-positions/create', [NetworkController::class, 'createMonitoredPosition'])->name('network.monitoredpositions.create')->middleware('can:edit monitored positions');
            });

            // Community - User Management
            Route::group(['middleware' => ['permission:view users'], 'prefix' => 'community'], function () {
                Route::get('/users', [UsersController::class, 'index'])->name('community.users.index');
                Route::get('/users/{id}', [UsersController::class, 'viewUser'])->name('community.users.view');
                Route::get('/users/{id}/reset/avatar', [UsersController::class, 'resetUserAvatar'])->name('community.users.reset.avatar')->middleware('can:edit user data');
                Route::post('/users/{id}/assign/role', [UsersController::class, 'assignUserRole'])->name('community.users.assign.role')->middleware('can:edit user data');
                Route::post('/users/{id}/assign/permission', [UsersController::class, 'assignUserPermission'])->name('community.users.assign.permission')->middleware('can:edit user data');
                Route::delete('/users/{id}/remove/role', [UsersController::class, 'removeUserRole'])->name('community.users.remove.role')->middleware('can:edit user data');
                Route::delete('/users/{id}/remove/permission', [UsersController::class, 'removeUserPermission'])->name('community.users.remove.permission')->middleware('can:edit user data');
            });
        });
    });
});

//Custom pages
Route::get('/{page_slug}', [CustomPagesController::class, 'viewPage'])->name('publications.custompages.view');
Route::post('/{page_slug}/response-submit', [CustomPagesController::class, 'submitResponse'])->name('publications.custompages.response-submit');
