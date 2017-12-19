<?php

namespace CMS\Models;

use CMS\Core\Model\Model;
use CMS\Models\Users\Users;

class Role extends Model
{
    protected $primaryKey = "role_id";

    public function permissions()
    {
        return $this->ownedByMany(Permission::class);
    }
    public function users()
    {
        return $this->ownedByMany(Users::class);
    }

}
