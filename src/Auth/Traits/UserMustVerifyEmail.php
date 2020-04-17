<?php

namespace LTFramework\Auth\Traits;

trait UserMustVerifyEmail {

    /**
     * This function check if email is verified
     * @return boolean
     */
    public function hasEmailVerified() 
    {
        
        return ! is_null($this->email_verified_at);

    }

    /**
     * This function send to the client the Email
     * 
     * @return void
     */
    public function sendVerificationEmail() 
    {

        $this->notify(app()->make('email.verification'));
        
    }

    /**
     * This function markEmailAsVerified
     * 
     * @return void
     */
    public function markEmailAsVerified() 
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}