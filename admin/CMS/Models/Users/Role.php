<?php

namespace CMS\Models\Users;

use CMS\Core\Model\Model;

class Role extends Model
{
    public $primaryKey = "role_id";
    public $table = "roles";

    public $allowed = [
        'name',
        'role_id',
        'permission_id',
    ];

    public $hidden = [
        'user_id'
    ];
    public function get_id()
    {
        return $this->role_id;
    }

    public function permissions()
    {
        return $this->ownedByMany(Permission::class,'roles_permissions');
    }
    public function users()
    {
        return $this->ownedByMany(Users::class,'users_roles');
    }

}
