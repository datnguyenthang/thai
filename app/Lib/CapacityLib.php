<?php

namespace App\Lib;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermision;

class CapacityLib {

    public static function hasCapacity($role, $permission){
        if(!$role) return false;
        if(!$permission) return false;

        $hasCapacity = RolePermision::where('role', '=', $role)->where('permission', '=', $permission)->first();
        if ($hasCapacity->status == ACTIVE) return true;

        return false;
    }
}