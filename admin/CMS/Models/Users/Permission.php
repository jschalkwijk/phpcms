<?php

namespace CMS\Models\Users;

use CMS\Core\Model\Model;

class Permission extends Model
{
    protected $primaryKey = "permission_id";
    public $table = "permissions";

    protected $allowed = [
        'permission_id',
        'name',
        'user_id',
        'role_id',
    ];

    public function get_id()
    {
        return $this->permission_id;
    }
    # Relations #
    public function roles()
    {
        return $this->ownedByMany(Role::class,'roles_permissions');
    }
    public function users()
    {
        $users1 = $this->ownedByMany(Users::class,'users_permissions');
        $users2 = $this->hasUsersThroughRoles();

        return array_merge($users1,$users2);
    }

    public function hasUsersThroughRoles()
    {
        return $this->ownsThroughMany(Users::class,Role::class,'users_roles','roles_permissions');
    }

//    public function giveRoleTo(array $roles)
//    {
//        if(array_filter($roles,'is_numeric') && !empty($this->roles())){
//            if(!$this->sync(Role::class,$this->roles(),$roles,'users_roles')){
//                return false;
//            }
//            // flash Message in controller.
//            return true;
//        } else {
//            $roles = $this->getRoles($roles);
//            return $this->saveMany($roles,'users_roles');
//        }
//    }

}
