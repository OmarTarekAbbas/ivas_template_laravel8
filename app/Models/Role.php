<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\Models\Role','user_has_role','role_id',"user_id");
    }
}
