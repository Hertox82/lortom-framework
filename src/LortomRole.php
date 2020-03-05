<?php
/**
 * User: hernan
 * Date: 30/10/2017
 * Time: 15:42
 */

namespace LTFramework;

use LTFramework\Model\LTModel as Model;
use DB;

class LortomRole extends Model
{
    protected $table = 'lt_roles';

    protected $permissions = [];

    public function permissions()
    {
        $this->permissions = $this->belongsToMany('LTFramework\LortomPermission','lt_roles_permissions','idRole','idPermission')->get();

        return $this;
    }

    public function toArray()
    {
        $return = [];

        foreach ($this->permissions as $permission)
        {
            $return[] = $permission;
        }

        return $return;
    }

    public function getPermissions() {
        if(empty($this->permissions)) {
            $this->permissions();
        }
        return $this->permissions;
    }

    public static function getPermissionsForTable($id) {
        if($id < 1) 
            return [];
        $Role = LortomRole::find($id);
        if(!$Role) 
            return [];

        $response = [];
        $listOfPermissions = $Role->getPermissions()->all();
        $listOfId = DB::table('lt_roles_permissions')->where('idRole',$id)->get()->all();

        $response = array_map(function($item, $panem){
            if($item->id == $panem->idPermission) {
                 return [
                        'idPerm'        => $panem->id,
                        'idPermission'  => $item->id,
                        'name'          => $item->name
                    ];
            } else {
                return [];
            }
        },$listOfPermissions,$listOfId);

        return $response;
    }
    public static function convertToSearch($input) {
        $search = $input['search'];
        $id = $input['idObject'];
        $listOfPermissions = LortomPermission::where('name','LIKE', '%'.$search.'%')->get()->all();

        if($id !== 0) {
            $Role = LortomRole::find($id);
            $PermissionIn = $Role->getPermissions()->all();
    
            foreach($PermissionIn as $perm) {
                for ($i = 0; $i < count($listOfPermissions); $i++) {
                    if ($perm->id === $listOfPermissions[$i]->id) {
                        array_splice($listOfPermissions,$i,1);
                        break;
                    }
                }
            }
        }

        return self::getPermissionForSearch($listOfPermissions);
    }

    public static function getPermissionForSearch($listOfPerm) {
        return array_map(function($item){
            return [
                'idPermission' => $item->id,
                'name'         => $item->name,
                'label'        => $item->name
            ];
        },$listOfPerm);
    }
}