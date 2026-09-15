<?php

namespace App\Notifications;

use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewNewsNotification extends Notification
{
    use Queueable;

    public function __construct(
        public News $news
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_news',
            'news_id' => $this->news->id,
            'title' => $this->news->title,
            'message' => 'A new news article has been published.',
        ];
    }
}