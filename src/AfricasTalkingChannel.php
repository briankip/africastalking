<?php

namespace NotificationChannels\AfricasTalking;

use NotificationChannels\AfricasTalking\Exceptions\CouldNotSendNotification;
use NotificationChannels\AfricasTalking\Events\MessageWasSent;
use NotificationChannels\AfricasTalking\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class AfricasTalkingChannel
{
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\AfricasTalking\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
