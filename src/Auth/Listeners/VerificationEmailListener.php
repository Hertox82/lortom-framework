<?php

namespace LTFramework\Auth\Listeners;

use Illuminate\Auth\Events\Registered;
use LTFramework\Contracts\UserMustVerifyEmail as ContractsUserMustVerifyEmail;


class VerificationEmailListener {


    /**
     * This function handle Event Registered in order to send email verification
     * 
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(Registered $event) {
        if($event->user instanceof ContractsUserMustVerifyEmail && ! $event->user->hasEmailVerified()) 
        {
            $event->user->sendVerificationEmail();
        }
    }
}