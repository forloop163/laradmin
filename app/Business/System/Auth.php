<?php
namespace App\Business\System;

use App\Models\Permission as PermissionModel;
use App\Business\System\Permission as PermissionBusiness;
use Illuminate\Support\Facades\Cache;
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
        $permissionBusiness = new PermissionBusiness(new PermissionModel);
        $allPermissionNames = $permissionBusiness->getAllPermissionName();

        foreach ($allPermissionNames as $permissionName) {
            Gate::define($permissionName, function ($user) use ($permissionName) {
                return $user->hasAccess($permissionName);
            });
        }
    }

    public function hasPermissionsSchema()
    {
        return Schema::hasTable('permissions') ? 1 : 0;
    }

    public function hasPermissionsSchemaFromCache() :bool
    {
        return Cache::rememberForever('has_permissions_table', function () {
            return $this->hasPermissionsSchema();
        }) === 1;
    }
}
