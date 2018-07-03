<?php
/**
 * User: hernan
 * Date: 23/10/2017
 * Time: 15:28
 */

namespace LTFramework;


use Illuminate\Database\Eloquent\Model;
use DB;

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

    public function roles()
    {
        $this->roles =  $this->belongsToMany('LTFramework\LortomRole','lt_users_roles','idUser','idRole')->get();

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

}



