<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Application\Service\TranslationServiceInterface;
use App\Shared\Application\Service\WebPushServiceInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\User\Domain\Entity\AvailableLanguage;
use Ds\Queue;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService implements WebPushServiceInterface
{
    private string $iconHref;
    private WebPush $webPush;
    private Queue $notifications;
    private string $publicKey;
    private TranslationServiceInterface $translationService;

    public function __construct(TranslationServiceInterface $translationService)
    {
        $this->translationService = $translationService;
        $this->iconHref = sprintf('%s/assets/images/favicon.ico', env('FRONT_URL'));
        $this->publicKey = env('WEB_NOTIFICATIONS_PUB');
        $this->notifications = new Queue();
        $this->webPush = new WebPush([
            "VAPID" => [
                "subject" => env('FRONT_URL'),
                "publicKey" => env('WEB_NOTIFICATIONS_PUB'),
                "privateKey" => env('WEB_NOTIFICATIONS_PRIV')
            ]
        ]);
    }

    public function addNotification(
        string $endpoint,
        string $auth,
        string $p256dh,
        string $lang,
        string $name,
        ?string $content,
        ?string $deactivationCode,
        AlarmId $alarmId
    ): self {
        $actions = [
            [
                'action' => 'goToAlarm',
                'title' => $this->translationService->getTranslation(
                    'translations.goToAlarm',
                    AvailableLanguage::from($lang)
                )
            ]
        ];
        if ($deactivationCode !== null) {
            $actions[] = [
                'action' => 'deactivate',
                'title' => $this->translationService->getTranslation(
                    'translations.deactivateAlarm',
                    AvailableLanguage::from($lang)
                )
            ];
        }
        $this->notifications->push(
            [
                'subscription' => $this->getSubscription($endpoint, $auth, $p256dh),
                'payload' => json_encode([
                    'notification' => [
                        'title' => $name,
                        'body' => $this->parseContent($content),
                        'data' => [
                            'alarmUrl' => $this->getAlarmUrl($alarmId),
                            'deactivationUrl' => $this->getDeactivationAlarmUrl($deactivationCode)
                        ],
                        'icon' => $this->iconHref,
                        'actions' => $actions
                    ]
                ], JSON_THROW_ON_ERROR)
            ]
        );

        return $this;
    }

    private function getSubscription(string $endpoint, string $auth, string $p256dh): Subscription
    {
        return Subscription::create([
            'endpoint' => $endpoint,
            'publicKey' => $this->publicKey,
            'keys' => [
                'p256dh' => $p256dh,
                'auth' => $auth
            ]
        ]);
    }

    private function parseContent(?string $content): string
    {
        if ($content === null) {
            return '';
        }

        return implode(
            '\n',
            array_filter(
                explode(
                    '\n',
                    strip_tags(preg_replace('/<\/[a-z]+>/', '\n', $content) ?? '')
                ),
                static fn(string $value) => $value !== ''
            )
        );
    }

    private function getAlarmUrl(AlarmId $alarmId): string
    {
        return sprintf('%s/alarms/%s', env('FRONT_URL'), $alarmId->getValue());
    }

    private function getDeactivationAlarmUrl(?string $code): ?string
    {
        if ($code === null) {
            return null;
        }

        return sprintf('%s/alarms/deactivate-single?code=%s', env('FRONT_URL'), $code);
    }

    public function addWelcomeNotification(string $endpoint, string $auth, string $p256dh, string $lang): self
    {
        $this->notifications->push(
            [
                'subscription' => $this->getSubscription($endpoint, $auth, $p256dh),
                'payload' => json_encode([
                    'notification' => [
                        'title' => $this->translationService->getTranslation(
                            'translations.notificationsActivated',
                            AvailableLanguage::from($lang)
                        ),
                        'icon' => $this->iconHref
                    ]
                ], JSON_THROW_ON_ERROR)
            ]
        );

        return $this;
    }

    public function send(): void
    {
        while (!$this->notifications->isEmpty()) {
            $notification = $this->notifications->pop();
            $this->webPush->sendOneNotification($notification['subscription'], $notification['payload']);
        }
    }
}
