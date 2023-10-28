<?php

namespace App\Listeners;

use App\Notifications\NewUserNotification;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;

class SendEmailNewUserListener
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $user->notify(new NewUserNotification());
    }
}
