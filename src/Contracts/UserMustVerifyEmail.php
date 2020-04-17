<?php


namespace LTFramework\Contracts;


interface UserMustVerifyEmail 
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasEmailVerified();

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendVerificationEmail();
}