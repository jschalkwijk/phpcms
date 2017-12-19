<?php

namespace CMS\Models;

use CMS\Core\Model\Model;

class Permission extends Model
{
    protected $primaryKey = "permission_id";

    public function roles()
    {
        return $this->ownedByMany(Role::class);
    }
    public function users()
    {
        return $this->ownedByMany(User::class);
    }

}
