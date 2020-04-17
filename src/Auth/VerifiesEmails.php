<?php

namespace LTFramework\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RedirectsUsers;
use LTFramework\Auth\Events\Verified;
use LTFramework\Auth\Exceptions\EmailVerificationExceptions;

trait VerifiesEmails
{
    use RedirectsUsers;
    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if(method_exists($this,'filteringData')) {
            $data = $this->filteringData($request);
        } else {
            $data = property_exists($this, 'data') ?  $this->data : [];
        }
        if(! $request->user()) 
        {
            return view('auth.verify', $data);
        }
        return $request->user()->hasEmailVerified()
                        ? redirect($this->redirectPath())
                        : view('auth.verify',$data);
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if(! $request->user())
        {
            $e = new EmailVerificationExceptions('Non Autorizzato');
            if(method_exists($this,'filteringData'))
                $e->prepareData($this->filteringData($request));

            throw $e;
        }
            
        
        if ($request->route('id') != $request->user()->getKey()) {
            $e = new EmailVerificationExceptions('Non Autorizzato');
            if(method_exists($this,'filteringData'))
                $e->prepareData($this->filteringData($request));

            throw $e;
        }

        if ($request->user()->hasEmailVerified()) {
            return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if(! $request->user())
            return redirect()->to('/email/verify');

        if ($request->user()->hasEmailVerified()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendVerificationEmail();

        return back()->with('resent', true);
    }
}