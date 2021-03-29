<?php
/**
 * User: hernan
 * Date: 24/10/2017
 * Time: 09:45
 */

namespace LTFramework\Auth;

use LTFramework\LortomUser;
use LTFramework\LortomRole;
use Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;

class LortomUserProvider
{

    protected $username;

    protected $userId;

    protected $password;

    protected $user;

    protected $payload;

    public function validateLogin(array $input, $isBackend = true)
    {
        array_walk_recursive($input,[$this->getNameClass(),'saveInput']);

        return $this->isValidate($isBackend);
    }

    /**
     * This function validate User
     * @param boolean $isBackend
     * @return boolean
     */
    protected function isValidate($isBackend)
    {
        // query to database User
        $query = $this->queryUser();
        
        // check if User is null
        if(is_null($query))
            return false;

        // check hash password are the same
        if(! Hash::check($this->password,$query->password))
            return false;
        
        // if User is a Backend User i do a special validation
        if($isBackend) {
           return $this->validateBackendUser($query);
        } else {
            return $this->validateFrontendUser($query);
        }
    }

    /**
     * This function validate a Backend User
     * @param \LTFramework\LortomUser $user
     * @return boolean
     */
    public function validateBackendUser(LortomUser $user) {
        // check the role
        $rolesEnv = env('LT_BACKEND_ROLES','');
        if(strlen($rolesEnv) == 0) {
            $this->user = $user;
            return true;
        } else {
            $roles = explode(',',$rolesEnv);
            $checkedRoles = $user->getRoles();
            if(multisite()->hasModelReadable(LTFramework\LortomRole::class)) {
                if(! Schema::hasColumn('lt_roles','site')) {
                    return false;
                }
                $checkedRoles = $checkedRoles->where('site',multisite()->getIdSite());
            }
            $getRoles = $checkedRoles->pluck('name')->toArray();
            foreach($getRoles as $role) {
                if(in_array($role,$roles)) {
                    $this->user = $user;
                    return true;
                }
            }
                return false;
        }
    }

    /**
     * This function validate a Frontend User
     * @param \LTFramework\LortomUser $user
     * @return boolean
     */
    protected function validateFrontendUser(LortomUser $user) {
        if(Schema::hasColumn('lt_user', 'activate_token')) 
        {
            if($user->activate_token) {
                return false;
            } else {
                $this->user = $user;
                return true;
            }
        } else {

            $role = 'Frontend';
            $checkedRoles = $user->getRoles();
            if(multisite()->hasModelReadable(LTFramework\LortomRole::class)) {
                if(! Schema::hasColumn('lt_roles','site')) {
                    return false;
                }
                $checkedRoles = $checkedRoles->where('site',multisite()->getIdSite());
            }
            $getRoles = $checkedRoles->pluck('name')->toArray();
            if(! in_array($role,$getRoles)) {
                return false;
            }

            $this->user = $user;
            return true;
        }
    }

    protected function getRole($role) {
        $LTRole = LortomRole::where('name',$role)->first();
        return $LTRole;
    }

    public function getToken()
    {
        return $this->retrieveTokenByCookies();
    }

    protected function retrieveTokenByCookies()
    {
        $tokenSplitted= [];
        $tokenSplitted[] = isset($_COOKIE['l_at']) ? $_COOKIE['l_at'] : null;
        $tokenSplitted[] = isset($_COOKIE['l_bt']) ? $_COOKIE['l_bt'] : null;
       
        $token = implode('.',$tokenSplitted);
        
        return (strlen($token) > 1) ? $token : null;
    }

    public function makeCookies($splittedToken) {
        list($lat,$lbt) = $splittedToken;
        $config = config('session');
        return [
            Cookie::make('l_at', $lat, env('COOKIE_LIFETIME',120), $config['path'], $config['domain'], $config['secure'],false,true,'Lax'),
            Cookie::make('l_bt', $lbt, env('COOKIE_LIFETIME',120), $config['path'], $config['domain'], $config['secure'],true,true,'Lax')
        ];
    }

    public function setToken($id = 0)
    {
        if(! $this->user) {
            if($id) {
                $this->userId = $id;
                $this->user = $this->queryById();
            }
            
        }
        $roles = $this->user->getRoles();

        $permissions = [];

        foreach($roles as $role) {
            if ($role instanceof LortomRole) {
                $perm = $role->getPermissions()->toArray();
                $permissions = array_merge($permissions,
                array_map(function($item){
                    return (object) [
                        'id'    => $item['id'],
                        'name'  => $item['name']
                    ];
            },$perm));
            }
        }
        $payload = [
            'sub'     => base64_encode($this->user->id),
            'name'    => $this->user->name,
            'perm'    => $permissions,
            'exp'     => 15,
            'iss'     => $_ENV['APP_NAME'],
            'created' => date('Y-m-d H:i:s')
        ];

        $token = JWT::encode($payload,'lortom_tomlor');

        return $token;
    }

    public function refreshToken($id)
    {
        return $this->refreshUser()->setToken($id);
    }

    public function splitToken($token) {
        $JWTPieces = explode(".", $token);
        $lbt = array_pop($JWTPieces);

        return [implode('.',$JWTPieces),$lbt];
    }

    public function validateToken($token)
    {

        try {
            $payload = JWT::decode($token, 'lortom_tomlor');
        }catch (\UnexpectedValueException $e)
        {
            return null;
        }

        $this->payload = $payload;

        $this->userId =  base64_decode($this->payload->sub);

        $this->user = $this->queryById();

        $nowTime = strtotime(date('Y-m-d H:i:s'));
        $created = strtotime($payload->created);
        $expiration = $created + ($payload->exp * 60);

        $check = $expiration - $nowTime;

        if($check >= 0)
        {
            // il token non è expirato
            return true;
        }
        else
        {
            //il token è expirato, bisogna farne uno nuovo
            return false;
        }
    }

    private function refreshUser() {
        $this->user = $this->queryById();
        return $this;
    }

    private function queryUser()
    {
        $query = LortomUser::where([['email',$this->username]])->first();

        return $query;
    }

    private function queryById()
    {
        $query = LortomUser::where([['id',$this->userId]])->first();

        return $query;
    }

    private function getNameClass()
    {
        return "LTFramework\\Auth\\LortomUserProvider";
    }


    protected function saveInput($item, $key)
    {
        $fn = ucfirst($key);
        $function = "set{$fn}";
        $this->$function($item);
    }

    protected function setUsername($value)
    {
        $this->username = $value;
    }

    protected function setPassword($value)
    {
        $this->password = $value;
    }

    public function getUser()
    {
        return $this->user;
    }
}