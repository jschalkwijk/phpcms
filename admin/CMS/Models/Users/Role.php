<?php

namespace CMS\Models\Users;

use CMS\Core\Model\Model;

class Role extends Model
{
    public $primaryKey = "role_id";
    public $table = "roles";

    public function permissions()
    {
        return $this->ownedByMany(Permission::class,'roles_permissions');
    }
    public function users()
    {
        return $this->ownedByMany(Users::class,'users_roles');
    }

}
