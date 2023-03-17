<?php

namespace Tagd\Core\Notifications\Consumers;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Tagd\Core\Models\Item\Tagd;

class TagdCreated extends Notification
{
    use Queueable;

    /**
     * @var Tagd
     */
    private $tagd;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Tagd $tagd)
    {
        $this->tagd = $tagd;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        \Log::info('----- to database');

        return [
            'title' => 'You have received a new tag!',
            // 'tagd' => $this->tagd,
        ];
    }
}
