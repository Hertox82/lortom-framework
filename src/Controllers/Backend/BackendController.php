<?php
/**
 * User: hernan
 * Date: 16/10/2017
 * Time: 16:39
 */
namespace LTFramework\Controllers\Backend;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LTFramework\LortomPermission;
use LTFramework\LortomUser;
use LTFramework\Services\Classes\LortomAuth;
use Illuminate\Http\Request;
use LTFramework\Services\Facades\LtAuth;

class BackendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $auth;

    public function __construct(LortomAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @Api({
        "description": "this is a fantastic API"
     *     })
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function populate(Request $request)
    {
        $User = $request->get('user');

        $listaPlugin = config('plugins');

        //ReSort the list of plugins
        usort($listaPlugin['plugins'],["LTFramework\\Controllers\\Backend\\BackendController","sort"]);

        $lista = array_map(function($plug)use($User){
            if(isset($plug['permission']))
            {
                if($User->hasPermission($plug['permission']))
                {
                    return [
                        'name' => $plug['PluginName'],
                        'href' => '/backend' . $plug['routingPath'],
                        'icon' => $plug['icon'],
                        'subMenu' => $this->setSubMenu(@$plug['subMenu'],$User)
                    ];
                }
            }
            else {
                return [
                    'name' => $plug['PluginName'],
                    'href' => '/backend' . $plug['routingPath'],
                    'icon' => $plug['icon'],
                    'subMenu' => $this->setSubMenu(@$plug['subMenu'],$User)
                ];
            }
        },$listaPlugin['plugins']);

        return response()->json(['menulista' => array_filter($lista)]);
    }



    public function login(Request $request)
    {
        return view('login');
    }

    public function requestLogin(Request $request)
    {
        $input = $request->only(['username','password']);

        if(!$this->auth->authenticate($input))
        {
            //errore durante il login, da fare in json
            return response()->json(['error' => 'username or password not find']);
        }

        $token = $this->auth->setToken();

        $User = $this->auth->getUser();

        $permission = array_filter(array_map_collection(function($perm){
            if($perm instanceof LortomPermission)
            {
                return $this->sanitizePermission($perm);
            }
        },$User->permissions()));

        $UserObj = ['username' => $User->email, 'name' => $User->name, 'permissions' => $permission];
        $response = ['token' => $token, 'user' => $UserObj];

        list($cookie1,$cookie2) = LtAuth::makeCookies(LtAuth::splitToken($token));

        return response()->json($response)->withCookie($cookie1)
                                          ->withCookie($cookie2);
    }

    private function sanitizePermission(LortomPermission $perm)
    {
        return [
            'id'    => $perm->id,
            'name'  => $perm->name
        ];
    }

    public function requestLogout(Request $request)
    {
        return response()->json(['message' => 'logged Out!'])->withCookie(cookie('l_at','',-1))->withCookie(cookie('l_bt','',-1));
    }

    public function requestEditMyProfile(Request $request)
    {

        $User = $request->get('user');

        $input = $request->only(['name','password']);


        if(isset($input['password']))
        {
            $User->password = bcrypt($input['password']);
        }

        if(isset($input['name'])){
            $User->name = $input['name'];
        }

        $User->save();

        $token = $this->auth->refreshToken($User->id);
        list($cookie1,$cookie2) = LtAuth::makeCookies(LtAuth::splitToken($token));

        $user = ['name' => $User->name, 'username' => base64_encode($User->id)];

        return response()->json(['message' => 'Well done! All is up to date', 'user' => $user])
        ->withCookie(cookie('l_at','',-1))->withCookie(cookie('l_bt','',-1))
        ->withCookie($cookie1)
        ->withCookie($cookie2);
    }

    static function sort($a,$b)
    {
        $a = $a['position'];
        $b = $b['position'];

        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
    }

    private function setSubMenu($lista, LortomUser &$user)
    {
        if(is_null($lista))
            return [];

        $array = [];

        $array = array_map(function($sub)use($user){

            if(isset($sub['permission']))
            {

                if($user->hasPermission($sub['permission']))
                {
                   return [
                        'name' => $sub['Name'],
                        'href' => '/backend' . $sub['subPath']
                    ];
                }
            }
            else
            {
                return [
                    'name' => $sub['Name'],
                    'href' => '/backend' . $sub['subPath']
                ];
            }

        },$lista);

        return array_filter($array);
    }


}