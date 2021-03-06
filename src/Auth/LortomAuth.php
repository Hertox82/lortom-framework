<?php
/**
 * User: hernan
 * Date: 23/10/2017
 * Time: 15:32
 */

namespace LTFramework\Auth;


class LortomAuth
{

    protected $userProvider;

    public function __construct(LortomUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate($input, $isBackend = true)
    {
        return $this->userProvider->validateLogin($input, $isBackend);
    }


    public function getToken()
    {
        return $this->userProvider->getToken();
    }

    public function setToken()
    {
       return $this->userProvider->setToken();
    }

    public function validateToken($token)
    {
        return $this->userProvider->validateToken($token);
    }

    public function refreshToken($id=0)
    {
        return $this->userProvider->refreshToken($id);
    }

    public function getUser()
    {
        return $this->userProvider->getUser();
    }

    public function splitToken($token) {
        return $this->userProvider->splitToken($token);
    }

    public function makeCookies($splittedToken) {
        return $this->userProvider->makeCookies($splittedToken);
    }

    public function isBackendUser(){
        return $this->userProvider->validateBackendUser($this->userProvider->getUser());
    }

}