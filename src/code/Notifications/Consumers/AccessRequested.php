<?php

namespace Tagd\Core\Notifications\Consumers;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Tagd\Core\Models\Actor\Reseller;

class AccessRequested extends Notification
{
    use Queueable;

    /**
     * @var Reseller
     */
    private $reseller;

    /**
     * @var AccessRequestId
     */
    private $accessRequestId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reseller $reseller, int $accessRequestId)
    {
        $this->reseller = $reseller;
        $this->accessRequestId = $accessRequestId;
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
        $resellerName = $this->reseller->name;

        return [
            'title' => "{$resellerName} has requested to access your items.",
            'reseller' => $this->reseller,
            'logo' => $this->reseller->avatar->url,
            'accessRequestId' => $this->accessRequestId,
        ];
    }
}
