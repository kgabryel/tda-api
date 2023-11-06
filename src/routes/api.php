<?php

use App\Alarm\Infrastructure\Controller\AlarmsController;
use App\Alarm\Infrastructure\Controller\NotificationController;
use App\Alarm\Infrastructure\Controller\PeriodicAlarmController;
use App\Alarm\Infrastructure\Controller\SingleAlarmController;
use App\Bookmark\Infrastructure\BookmarksController;
use App\Catalog\Infrastructure\CatalogsController;
use App\Color\Infrastructure\ColorsController;
use App\File\Infrastructure\FilesController;
use App\Note\Infrastructure\NotesController;
use App\Task\Infrastructure\Controller\PeriodicTaskController;
use App\Task\Infrastructure\Controller\SingleTaskController;
use App\Task\Infrastructure\Controller\TasksController;
use App\User\Infrastructure\Controller\AuthController;
use App\User\Infrastructure\Controller\UserController;
use App\Video\Infrastructure\VideosController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

Route::group(['prefix' => 'facebook'], static function() {
    Route::get('/redirect', [AuthController::class, 'getRedirectUrl']);
    Route::post('/login', [AuthController::class, 'facebookLogin']);
});

Route::post('/send-reset-password', [AuthController::class, 'sendResetPasswordEmail']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => ['auth:api', 'startTransaction', 'commitTransaction', 'emitEvents']], static function() {
    Route::group(['prefix' => 'dashboard'], static function() {
        Route::delete('/notes/{id}', [NotesController::class, 'undoFromDashboard'])
            ->whereNumber('id');
        Route::delete('/bookmarks/{id}', [BookmarksController::class, 'undoFromDashboard'])
            ->whereNumber('id');
        Route::delete('/files/{id}', [FilesController::class, 'undoFromDashboard'])
            ->whereNumber('id');
        Route::delete('/videos/{id}', [VideosController::class, 'undoFromDashboard'])
            ->whereNumber('id');
        Route::delete('/catalogs/{id}', [CatalogsController::class, 'undoFromDashboard'])
            ->whereNumber('id');
    });

    Route::group(['prefix' => 'notes', 'as' => 'notes.'], static function() {
        Route::get('/{id}', [NotesController::class, 'findById'])
            ->whereNumber('id')
            ->name('findById');
        Route::get('/', [NotesController::class, 'find']);
        Route::post('/', [NotesController::class, 'create']);
        Route::delete('/{id}', [NotesController::class, 'delete'])->whereNumber('id');
        Route::put('/{id}', [NotesController::class, 'update'])->whereNumber('id');
    });

    Route::group(['prefix' => 'bookmarks', 'as' => 'bookmarks.'], static function() {
        Route::get('/{id}', [BookmarksController::class, 'findById'])
            ->whereNumber('id')
            ->name('findById');
        Route::get('/', [BookmarksController::class, 'find']);
        Route::post('/', [BookmarksController::class, 'create']);
        Route::delete('/{id}', [BookmarksController::class, 'delete'])->whereNumber('id');
        Route::put('/{id}', [BookmarksController::class, 'update'])->whereNumber('id');
    });

    Route::group(['prefix' => 'alarms', 'as' => 'alarms.'], static function() {
        Route::get('/{id}', [AlarmsController::class, 'findById'])
            ->whereUuid('id')
            ->name('findById');
        Route::get('/', [AlarmsController::class, 'find']);
        Route::post('/create/single', [SingleAlarmController::class, 'create']);
        Route::post('/create/periodic', [PeriodicAlarmController::class, 'create']);
        Route::post('/deactivate', [SingleAlarmController::class, 'deactivate']);
        Route::delete('/{id}', [AlarmsController::class, 'delete'])->whereUuid('id');
        Route::patch('/{id}/single', [SingleAlarmController::class, 'update'])->whereUuid('id');
        Route::patch('/{id}/periodic', [PeriodicAlarmController::class, 'update'])->whereUuid('id');
        Route::post('/{id}/check', [SingleAlarmController::class, 'check'])->whereUuid('id');
        Route::post('/{id}/uncheck', [SingleAlarmController::class, 'uncheck'])->whereUuid('id');
        Route::post('/{id}/single/notifications', [SingleAlarmController::class, 'addNotification'])
            ->whereUuid('id');
        Route::post('/{id}/periodic/notifications', [PeriodicAlarmController::class, 'addNotification'])
            ->whereUuid('id');
        Route::post('/{alarmId}/catalogs', [AlarmsController::class, 'addCatalog'])->whereUuid('alarmId');
        Route::delete('/{alarmId}/catalogs/{catalogId}', [AlarmsController::class, 'removeCatalog'])
            ->whereUuid('alarmId')
            ->whereNumber('catalogId');
        Route::group(['prefix' => 'notifications'], static function() {
            Route::delete('/{id}', [NotificationController::class, 'delete'])->whereNumber('id');
            Route::post('/{id}/check', [NotificationController::class, 'check'])->whereNumber('id');
            Route::post('/{id}/uncheck', [NotificationController::class, 'uncheck'])->whereNumber('id');
            Route::group(['prefix' => 'types'], static function() {
                Route::get('/', [NotificationController::class, 'findAllNotificationTypes']);
            });
        });
        Route::patch('/{id}/activate', [PeriodicAlarmController::class, 'activate'])->whereUuid('id');
        Route::patch('/{id}/deactivate', [PeriodicAlarmController::class, 'deactivate'])->whereUuid('id');
    });

    Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], static function() {
        Route::get('/{id}', [TasksController::class, 'findById'])
            ->whereUuid('id')
            ->name('findById');
        Route::get('/', [TasksController::class, 'find']);
        Route::post('/create/single', [SingleTaskController::class, 'create']);
        Route::post('/create/periodic', [PeriodicTaskController::class, 'create']);
        Route::post('/{id}/change-status', [SingleTaskController::class, 'changeStatus'])->whereUuid('id');
        Route::delete('/{id}', [TasksController::class, 'delete'])->whereUuid('id');
        Route::patch('/{id}/single', [SingleTaskController::class, 'update'])->whereUuid('id');
        Route::patch('/{id}/periodic', [PeriodicTaskController::class, 'update'])->whereUuid('id');
        Route::post('/{taskId}/catalogs', [TasksController::class, 'addCatalog'])->whereUuid('taskId');
        Route::post('/{taskId}/notes', [TasksController::class, 'addNote'])->whereUuid('taskId');
        Route::post('/{taskId}/bookmarks', [TasksController::class, 'addBookmark'])->whereUuid('taskId');
        Route::post('/{taskId}/videos', [TasksController::class, 'addVideo'])->whereUuid('taskId');
        Route::post('/{taskId}/files', [TasksController::class, 'addFile'])->whereUuid('taskId');
        Route::delete('/{taskId}/catalogs/{catalogId}', [TasksController::class, 'removeCatalog'])
            ->whereUuid('taskId')
            ->whereNumber('catalogId');
        Route::delete('/{taskId}/notes/{noteId}', [TasksController::class, 'removeNote'])
            ->whereUuid('noteId')
            ->whereNumber('noteId');
        Route::delete('/{taskId}/bookmarks/{bookmarkId}', [TasksController::class, 'removeBookmark'])
            ->whereUuid('taskId')
            ->whereNumber('bookmarkId');
        Route::delete('/{taskId}/files/{fileId}', [TasksController::class, 'removeFile'])
            ->whereUuid('taskId')
            ->whereNumber('fileId');
        Route::delete('/{taskId}/videos/{videoId}', [TasksController::class, 'removeVideo'])
            ->whereUuid('taskId')
            ->whereNumber('videoId');
        Route::group(['prefix' => 'statuses'], static function() {
            Route::get('/', [TasksController::class, 'findAllStatuses']);
        });
        Route::patch('/{id}/activate', [PeriodicTaskController::class, 'activate'])->whereUuid('id');
        Route::patch('/{id}/deactivate', [PeriodicTaskController::class, 'deactivate'])->whereUuid('id');
    });

    Route::group(['prefix' => 'files', 'as' => 'files.'], static function() {
        Route::get('/{id}', [FilesController::class, 'findById'])
            ->whereNumber('id')
            ->name('findById');
        Route::get('/', [FilesController::class, 'find']);
        Route::post('/', [FilesController::class, 'create']);
        Route::get('/{id}/download', [FilesController::class, 'download'])->whereNumber('id');
        Route::delete('/{id}', [FilesController::class, 'delete'])->whereNumber('id');
        Route::patch('/{id}', [FilesController::class, 'update'])->whereNumber('id');
    });

    Route::group(['prefix' => 'videos', 'as' => 'videos.'], static function() {
        Route::get('/{id}', [VideosController::class, 'findById'])
            ->whereNumber('id')
            ->name('findById');
        Route::get('/', [VideosController::class, 'find'])->name('find');
        Route::post('/', [VideosController::class, 'create']);
        Route::delete('/{id}', [VideosController::class, 'delete'])->whereNumber('id');
        Route::patch('/{id}', [VideosController::class, 'update'])->whereNumber('id');
    });

    Route::group(['prefix' => 'colors', 'as' => 'colors.'], static function() {
        Route::get('/{id}', [ColorsController::class, 'findById'])
            ->whereNumber('id')
            ->name('findById');
        Route::get('/', [ColorsController::class, 'findAll']);
        Route::post('/', [ColorsController::class, 'create']);
        Route::delete('/{id}', [ColorsController::class, 'delete'])->whereNumber('id');
    });

    Route::group(['prefix' => 'catalogs', 'as' => 'catalogs.'], static function() {
        Route::get('/{id}', [CatalogsController::class, 'findById'])
            ->whereNumber('id')
            ->name('findById');
        Route::get('/', [CatalogsController::class, 'find']);
        Route::post('/', [CatalogsController::class, 'create']);
        Route::delete('/{id}', [CatalogsController::class, 'delete'])->whereNumber('id');
        Route::put('/{id}', [CatalogsController::class, 'update'])->whereNumber('id');
        Route::delete('/{catalogId}/tasks/{taskId}', [CatalogsController::class, 'removeTask'])
            ->whereNumber('catalogId')
            ->whereUuid('taskId');
        Route::delete('/{catalogId}/alarms/{alarmId}', [CatalogsController::class, 'removeAlarm'])
            ->whereNumber('catalogId')
            ->whereUuid('alarmId');
        Route::delete('/{catalogId}/notes/{noteId}', [CatalogsController::class, 'removeNote'])
            ->whereNumber('catalogId')
            ->whereNumber('noteId');
        Route::delete('/{catalogId}/bookmarks/{bookmarkId}', [CatalogsController::class, 'removeBookmark'])
            ->whereNumber('catalogId')
            ->whereNumber('bookmarkId');
        Route::delete('/{catalogId}/files/{fileId}', [CatalogsController::class, 'removeFile'])
            ->whereNumber('catalogId')
            ->whereNumber('fileId');
        Route::delete('/{catalogId}/videos/{videoId}', [CatalogsController::class, 'removeVideo'])
            ->whereNumber('catalogId')
            ->whereNumber('videoId');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], static function() {
        Route::get('/', [UserController::class, 'getSettings']);
        Route::get('/email-state', [UserController::class, 'getEmailState'])->name('getEmailState');
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/change-email', [UserController::class, 'changeEmail']);
        Route::post('/confirm-email', [UserController::class, 'confirmEmail']);
        Route::post('/send-code', [UserController::class, 'sendCode']);
        Route::post('/default-pagination', [UserController::class, 'changeDefaultPagination']);
        Route::post('/notification-language', [UserController::class, 'changeNotificationLanguage']);
        Route::post('/subscription', [UserController::class, 'addPushSubscription']);
        Route::post('/{field}', [UserController::class, 'changeSettings']);
    });
});
