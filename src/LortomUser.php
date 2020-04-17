<?php
/**
 * User: hernan
 * Date: 23/10/2017
 * Time: 15:28
 */

namespace LTFramework;


use LTFramework\Model\LTModel as Model;
use DB;
use Illuminate\Notifications\Notifiable;
use LTFramework\Exceptions\LTHttpException;
use LTFramework\Auth\Traits\UserMustVerifyEmail;
use LTFramework\Contracts\UserMustVerifyEmail as ContractsUserMustVerifyEmail;
use Schema;

class LortomUser extends Model implements ContractsUserMustVerifyEmail
{
    use UserMustVerifyEmail, Notifiable;

    protected $table = 'lt_user';


    protected $roles = [];

    protected $fillable = ['name','email','password'];

    /**
     * @var array
     */
    protected $permissions;

    /**
     * This function check if User has a specific Role
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if(empty($this->roles))
            $this->roles();
        
        foreach ($this->roles as $r)
        {
            if($r instanceof LortomRole)
            {
                if($r->name == $role)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * This function check if a User has roles
     * @return boolean
     */
    public function hasRoles()
    {
        if(empty($this->roles))
            $this->roles();

        return (count($this->roles) > 0);
    }

    /**
     * This function check if an User has a specific Permission
     * @param string $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        if(empty ($this->permissions))
        {
            $this->permissions();
        }

        foreach ($this->permissions as $p)
        {

            if($p instanceof LortomPermission)
            {
                if($p->name == $permission)
                {
                    return true;
                }
            }
        }

        return false;
    }


    public function permissions()
    {
        $this->permissions = [];

        if(empty($this->roles))
        {
            $this->roles();
        }

        foreach ($this->roles as $role)
        {
            if($role instanceof LortomRole)
            {
                $this->permissions = array_merge($this->permissions, $role->permissions()->toArray());
            }
        }

        return $this->permissions;
    }

    public function getRoles()
    {
        $this->roles();

        return $this->roles;
    }

    public function btmRoles() {
        return $this->belongsToMany('LTFramework\LortomRole','lt_users_roles','idUser','idRole');
    }

    public function roles()
    {
        $roles =  $this->btmRoles()->get();

        if(multisite()->hasModelReadable(LortomRole::class)) {
            if(! Schema::hasColumn('lt_roles','site')) {
                throw new LTHttpException(
                    response()->json(['error' => "lt_components not have site column"],404)
                );
            }
            $roles = $roles->where('site',multisite()->getIdSite());
        } else {
            if(Schema::hasColumn('lt_roles','site')) {
                $roles = $roles->where('site', null);
            }
        }

        $this->roles = $roles;

        return $this;
    }

    public function RoletoArray()
    {
        $return = [];

        foreach ($this->roles as $role)
        {
            $return[] = $role;
        }

        return $return;
    }

    public static function getRolesForTable($id) {
        if($id < 1) 
            return [];
        $User = LortomUser::find($id);
        if(!$User) 
            return [];
        
        $response = [];
        $listOfRoles = $User->getRoles();

        // pr($listOfRoles,1);

        $idList = $listOfRoles->pluck('id')->toArray();
        
        $listOfId = DB::table('lt_users_roles')->where('idUser',$id)->whereIn('idRole',$idList)->get()->all();

        $response = array_map(function($item, $panem){
            if($item->id == $panem->idRole) {

                    return [
                        'idUserRole'        => $panem->id,
                        'idRole'  => $item->id,
                        'name'          => $item->name
                    ];
            } else {
                return [];
            }
        },$listOfRoles->all(),$listOfId);
        return array_filter($response);
    }

    public static function convertToSearch($input) {
        $id = $input['idObject'];
        // inizializzo la query per la ricerca dei ruoli
        $listOfRoles = LortomRole::where('name','LIKE', '%'.$input['search'].'%');
        // controllo che non sia un multisito con database condiviso
        if(multisite()->hasModelReadable(LortomRole::class)) {
            if(! Schema::hasColumn('lt_roles','site')) {
                throw new LTHttpException(response()
                ->json(['error' => "lt_components not have site column"],404));
            } 
            //completo la query
            $listOfRoles = $listOfRoles->where('site',multisite()->getIdSite());
        } else {
            if(Schema::hasColumn('lt_roles','site')) {
                $listOfRoles = $listOfRoles->where('site', null);
            }
        }
        // prendo i risultati
        $listOfRoles = $listOfRoles->get()->all();
        //pr($listOfRoles,1);
        if($id !== 0) {
            $User = self::find($id);
            $rolesIn = $User->getRoles();
            $rolesIn = $rolesIn->all();

            foreach($rolesIn as $role) {
                for($i = 0; $i < count($listOfRoles); $i++) {
                    if($role->id === $listOfRoles[$i]->id) {
                        array_splice($listOfRoles,$i,1);
                        break;
                    }
                }
            }
        }
        return self::getRolesForSearch($listOfRoles);
    }

    public static function getRolesForSearch($listOfItem) {
        return array_map(function($item){
            return [
                'idRole'    => $item->id,
                'name'      => $item->name,
                'label'     => $item->name
            ];
        },$listOfItem);
    }
}
