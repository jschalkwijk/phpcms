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
    ];

    public function get_id()
    {
        return $this->permission_id;
    }
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

}
