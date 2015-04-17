<?php namespace Keep\Handlers\Events;

use App;
use Keep\Events\UserWasActivatedEvent;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class EmailActivatedAccount implements ShouldBeQueued {

    /**
     * Handle the event.
     *
     * @param  UserWasActivatedEvent $event
     *
     * @return void
     */
    public function handle(UserWasActivatedEvent $event)
    {
        $mailer = App::make('Keep\Mailers\UserMailer');

        $mailer->sendAccountActivatedConfirmation($event->getUser());
    }

}
