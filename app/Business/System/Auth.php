<?php
namespace App\Business\System;

use App\Models\System\Permission as PermissionModel;
use App\Business\System\Permission as PermissionBusiness;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class Auth
{
    public function authUser()
    {
        return \Illuminate\Support\Facades\Auth::user();
    }

    public function registerGates()
    {
        $permissionBusiness = new PermissionBusiness;
        $allPermissionNames = $permissionBusiness->getAllPermissionName();

        foreach ($allPermissionNames as $permissionName) {
            Gate::define($permissionName, function ($user) use ($permissionName) {
                return $user->hasAccess($permissionName);
            });
        }
    }
}
