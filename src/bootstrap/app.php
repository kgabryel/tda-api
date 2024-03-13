<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

use App\Alarm\Application\AlarmManagerInterface;
use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Application\NotificationsTypesRepository as NotificationsTypesRepositoryInterface;
use App\Alarm\Application\Notificator as AlarmsNotificator;
use App\Alarm\Application\ReadRepository as AlarmsReadRepositoryInterface;
use App\Alarm\Domain\Service\NotificationsService as NotificationsServiceInterface;
use App\Alarm\Domain\WriteRepository as AlarmsWriteRepositoryInterface;
use App\Alarm\Infrastructure\Manager\AlarmManager;
use App\Alarm\Infrastructure\Manager\NotificationManager;
use App\Alarm\Infrastructure\Repository\AlarmsReadRepository;
use App\Alarm\Infrastructure\Repository\AlarmsWriteRepository;
use App\Alarm\Infrastructure\Repository\NotificationsTypesRepository;
use App\Alarm\Infrastructure\Service\NotificationsService;
use App\Bookmark\Application\BookmarkManagerInterface;
use App\Bookmark\Application\FaviconServiceInterface;
use App\Bookmark\Application\Notificator as BookmarksNotificator;
use App\Bookmark\Application\ReadRepository as BookmarksReadRepositoryInterface;
use App\Bookmark\Domain\WriteRepository as BookmarksWriteRepositoryInterface;
use App\Bookmark\Infrastructure\BookmarkManager;
use App\Bookmark\Infrastructure\BookmarksReadRepository;
use App\Bookmark\Infrastructure\BookmarksWriteRepository;
use App\Bookmark\Infrastructure\FaviconService;
use App\Catalog\Application\CatalogManagerInterface;
use App\Catalog\Application\Notificator as CatalogsNotificator;
use App\Catalog\Application\ReadRepository as CatalogsReadRepositoryInterface;
use App\Catalog\Domain\WriteRepository as CatalogsWriteRepositoryInterface;
use App\Catalog\Infrastructure\CatalogManager;
use App\Catalog\Infrastructure\CatalogsReadRepository;
use App\Catalog\Infrastructure\CatalogsWriteRepository;
use App\Color\Application\ColorManagerInterface;
use App\Color\Application\ReadRepository as ColorsReadRepositoryInterface;
use App\Color\Domain\WriteRepository as ColorsWriteRepositoryInterface;
use App\Color\Infrastructure\ColorManager;
use App\Color\Infrastructure\ColorsReadRepository;
use App\Color\Infrastructure\ColorsWriteRepository;
use App\Core\BusUtils;
use App\Core\Cqrs\AsyncEventBus;
use App\Core\Cqrs\EventBus;
use App\Core\Cqrs\EventsConfig;
use App\Core\Notification\NotificationService;
use App\File\Application\DeleteFileServiceInterface;
use App\File\Application\FileManagerInterface;
use App\File\Application\Notificator as FilesNotificator;
use App\File\Application\ReadRepository as FilesReadRepositoryInterface;
use App\File\Domain\WriteRepository as FilesWriteRepositoryInterface;
use App\File\Infrastructure\DeleteFileService;
use App\File\Infrastructure\FileManager;
use App\File\Infrastructure\FilesReadRepository;
use App\File\Infrastructure\FilesWriteRepository;
use App\Note\Application\NoteManagerInterface;
use App\Note\Application\Notificator as NotesNotificator;
use App\Note\Application\ReadRepository as NotesReadRepositoryInterface;
use App\Note\Domain\WriteRepository as NotesWriteRepositoryInterface;
use App\Note\Infrastructure\NoteManager;
use App\Note\Infrastructure\NotesReadRepository;
use App\Note\Infrastructure\NotesWriteRepository;
use App\Shared\Application\AlarmsTypesRepository;
use App\Shared\Application\Service\TranslationServiceInterface;
use App\Shared\Application\Service\WebPushServiceInterface;
use App\Shared\Application\TasksTypesRepository as TasksTypesRepositoryInterface;
use App\Shared\Application\UuidInterface;
use App\Shared\Infrastructure\Service\TranslationService;
use App\Shared\Infrastructure\Service\WebPushService;
use App\Shared\Infrastructure\Uuid;
use App\Task\Application\Notificator as TasksNotificator;
use App\Task\Application\ReadRepository as TasksReadRepositoryInterface;
use App\Task\Application\TaskManagerInterface;
use App\Task\Application\TasksStatusesRepository as TasksStatusesRepositoryInterface;
use App\Task\Domain\Repository\TasksStatusesWriteRepository;
use App\Task\Domain\Repository\TasksWriteRepository as TasksWriteRepositoryInterface;
use App\Task\Infrastructure\Repository\TasksReadRepository;
use App\Task\Infrastructure\Repository\TasksStatusesRepository;
use App\Task\Infrastructure\Repository\TasksWriteRepository;
use App\Task\Infrastructure\Repository\TasksTypesRepository;
use App\Task\Infrastructure\TaskManager;
use App\User\Application\MailServiceInterface;
use App\User\Application\ReadRepository as UserReadRepositoryInterface;
use App\User\Application\SettingsManagerInterface;
use App\User\Application\TokenService as TokenServiceInterface;
use App\User\Application\UserManagerInterface;
use App\User\Domain\PasswordService as PasswordServiceInterface;
use App\User\Domain\WriteRepository as UserWriteRepositoryInterface;
use App\User\Infrastructure\MailService;
use App\User\Infrastructure\PasswordService;
use App\User\Infrastructure\SettingsManager;
use App\User\Infrastructure\TokenService;
use App\User\Infrastructure\UserManager;
use App\User\Infrastructure\UserReadRepository;
use App\User\Infrastructure\UserWriteRepository;
use App\Video\Application\Notificator as VideosNotificator;
use App\Video\Application\ReadRepository as VideosReadRepositoryInterface;
use App\Video\Application\VideoManagerInterface;
use App\Video\Application\YtServiceInterface;
use App\Video\Domain\WriteRepository as VideosWriteRepositoryInterface;
use App\Video\Infrastructure\VideoManager;
use App\Video\Infrastructure\VideosReadRepository;
use App\Video\Infrastructure\VideosWriteRepository;
use App\Video\Infrastructure\YtService;

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(Illuminate\Contracts\Http\Kernel::class, \App\Core\Framework\Kernel::class);
$app->singleton(Illuminate\Contracts\Console\Kernel::class, \App\Core\Framework\Console\Kernel::class);
$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, \App\Core\Framework\ExceptionHandler::class);
$app->singleton(VideoManagerInterface::class, VideoManager::class);
$app->singleton(YtServiceInterface::class, YtService::class);
$app->singleton(TasksTypesRepositoryInterface::class, TasksTypesRepository::class);
$app->singleton(AlarmsTypesRepository::class, AlarmsReadRepository::class);
$app->singleton(ColorManagerInterface::class, ColorManager::class);
$app->singleton(BookmarksReadRepositoryInterface::class, BookmarksReadRepository::class);
$app->singleton(BookmarksWriteRepositoryInterface::class, BookmarksWriteRepository::class);
$app->singleton(CatalogsReadRepositoryInterface::class, CatalogsReadRepository::class);
$app->singleton(CatalogsWriteRepositoryInterface::class, CatalogsWriteRepository::class);
$app->singleton(FilesReadRepositoryInterface::class, FilesReadRepository::class);
$app->singleton(FilesWriteRepositoryInterface::class, FilesWriteRepository::class);
$app->singleton(VideosReadRepositoryInterface::class, VideosReadRepository::class);
$app->singleton(VideosWriteRepositoryInterface::class, VideosWriteRepository::class);
$app->singleton(NotesReadRepositoryInterface::class, NotesReadRepository::class);
$app->singleton(NotesWriteRepositoryInterface::class, NotesWriteRepository::class);
$app->singleton(VideosWriteRepositoryInterface::class, VideosWriteRepository::class);
$app->singleton(ColorsReadRepositoryInterface::class, ColorsReadRepository::class);
$app->singleton(ColorsWriteRepositoryInterface::class, ColorsWriteRepository::class);
$app->singleton(CatalogManagerInterface::class, CatalogManager::class);
$app->singleton(NoteManagerInterface::class, NoteManager::class);
$app->singleton(BookmarkManagerInterface::class, BookmarkManager::class);
$app->singleton(FileManagerInterface::class, FileManager::class);
$app->singleton(FaviconServiceInterface::class, FaviconService::class);
$app->singleton(UuidInterface::class, Uuid::class);
$app->singleton(DeleteFileServiceInterface::class, DeleteFileService::class);
$app->singleton(FileManagerInterface::class, FileManager::class);
$app->singleton(AlarmsReadRepositoryInterface::class, AlarmsReadRepository::class);
$app->singleton(AlarmsWriteRepositoryInterface::class, AlarmsWriteRepository::class);
$app->singleton(NotificationsTypesRepositoryInterface::class, NotificationsTypesRepository::class);
$app->singleton(AlarmManagerInterface::class, AlarmManager::class);
$app->singleton(NotificationsServiceInterface::class, NotificationsService::class);
$app->singleton(NotificationManagerInterface::class, NotificationManager::class);
$app->singleton(TasksStatusesRepositoryInterface::class, TasksStatusesRepository::class);
$app->singleton(TasksWriteRepositoryInterface::class, TasksWriteRepository::class);
$app->singleton(TasksReadRepositoryInterface::class, TasksReadRepository::class);
$app->singleton(TaskManagerInterface::class, TaskManager::class);
$app->singleton(TasksStatusesWriteRepository::class, TasksStatusesRepository::class);
$app->singleton(UserReadRepositoryInterface::class, UserReadRepository::class);
$app->singleton(SettingsManagerInterface::class, SettingsManager::class);
$app->singleton(UserManagerInterface::class, UserManager::class);
$app->singleton(PasswordServiceInterface::class, PasswordService::class);
$app->singleton(AlarmsNotificator::class, NotificationService::class);
$app->singleton(BookmarksNotificator::class, NotificationService::class);
$app->singleton(CatalogsNotificator::class, NotificationService::class);
$app->singleton(FilesNotificator::class, NotificationService::class);
$app->singleton(NotesNotificator::class, NotificationService::class);
$app->singleton(TasksNotificator::class, NotificationService::class);
$app->singleton(VideosNotificator::class, NotificationService::class);
$app->singleton(UserWriteRepositoryInterface::class, UserWriteRepository::class);
$app->singleton(TokenServiceInterface::class, TokenService::class);
$app->singleton(MailServiceInterface::class, MailService::class);
$app->singleton(EventsConfig::class, static fn() => EventsConfig::getInstance());
$app->singleton(BusUtils::class);
$app->singleton(EventBus::class);
$app->singleton(AsyncEventBus::class);
$app->singleton(WebPushServiceInterface::class, WebPushService::class);
$app->singleton(TranslationServiceInterface::class, TranslationService::class);
/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
