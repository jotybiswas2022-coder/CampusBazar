<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Generic in-app notification used across CampusBazar.
 *
 * When a mailer is configured this can also be delivered by email; the
 * application works fully with the database channel when email is unavailable.
 */
class MarketplaceNotification extends Notification
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public string $title,
        public string $message,
        public ?string $url = null,
        public string $level = 'info',
        public array $meta = [],
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'level' => $this->level,
            'meta' => $this->meta,
        ];
    }
}
