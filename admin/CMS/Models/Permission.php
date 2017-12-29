<?php

namespace CMS\Models;

use CMS\Core\Model\Model;

class Permission extends Model
{
    protected $primaryKey = "permission_id";
    public $table = "permissions";

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
        return $this->ownedByMany(User::class);
    }

}
