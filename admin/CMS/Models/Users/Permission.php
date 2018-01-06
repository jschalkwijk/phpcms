<?php

namespace CMS\Models\Users;

use CMS\Core\Model\Model;

class Permission extends Model
{
    protected $primaryKey = "permission_id";
    public $table = "permissions";

    protected $allowed = [
        'permission_id',
//        'user_id',
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
        return $this->ownedByMany(Users::class,'users_permissions');
    }

}
