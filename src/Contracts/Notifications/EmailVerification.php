<?php

namespace LTFramework\Contracts\Notifications;


interface EmailVerification 
{
    public function toMail($notifiable);
}