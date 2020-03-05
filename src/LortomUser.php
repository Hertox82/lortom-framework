<?php
/**
 * User: hernan
 * Date: 23/10/2017
 * Time: 15:28
 */

namespace LTFramework;


use LTFramework\Model\LTModel as Model;
use DB;
use LTFramework\Exceptions\LTHttpException;
use Schema;

class LortomUser extends Model
{
    protected $table = 'lt_user';


    protected $roles = [];

    /**
     * @var array
     */
    protected $permissions;

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

    public function hasRoles()
    {
        if(empty($this->roles))
            $this->roles();

        return (count($this->roles) > 0);
    }


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
        $this->roles =  $this->btmRoles()->get();

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
            if(multisite()->hasModelReadable(LortomRole::class)) {
                if(! Schema::hasColumn('lt_roles','site')) {
                    throw new LTHttpException(
                        response()->json(['error' => "lt_components not have site column"],404)
                    );
                }
                $listOfRoles = $listOfRoles->where('site',multisite()->getIdSite());
            }
            $listOfId = DB::table('lt_users_roles')->where('idUser',$id)->get()->all();

            $response = array_map(function($item, $panem){
                if($item->id == $panem->idRole) {
                    if(multisite()->hasModelReadable(LortomRole::class)) {
                        if(!Schema::hasColumn('lt_roles','site')) {

                        }
                    }
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
        if(multisite()->hasModelReadable(LTFramework\LortomRole::class)) {
            if(! Schema::hasColumn('lt_roles','site')) {
                throw new LTHttpException(response()
                ->json(['error' => "lt_components not have site column"],404));
            } 
            //completo la query
            $listOfRoles = $listOfRoles->where('site',multisite()->getIdSite());
        }
        // prendo i risultati
        $listOfRoles = $listOfRoles->get()->all();
        if($id !== 0) {
            $User = self::find($id);
            $rolesIn = $User->getRoles();
            if(multisite()->hasModelReadable(LTFramework\LortomRole::class)) {
                if(! Schema::hasColumn('lt_roles','site')) {
                    throw new LTHttpException(response()
                    ->json(['error' => "lt_components not have site column"],404));
                } 
                $rolesIn = $rolesIn->where('site',multisite()->getIdSite());
            }
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
