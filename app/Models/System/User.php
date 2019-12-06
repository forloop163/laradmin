<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Collection;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'mobile', 'email', 'password'];

    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany('App\Models\System\Log', 'user_id');
    }


    public function roles()
    {
        return $this->belongsToMany('App\Models\System\Role', 'user_role', 'user_id', 'role_id');
    }

    public function hasPermissions($withoutApi = false)
    {
        $permissions = collect();

        $this->roles()->where('active', 1)->get()->map(function ($role) use ($permissions, $withoutApi) {
            $role->permissions()->when($withoutApi, function ($query) {
                return $query->where('is_api', 0);
            })->orderBy('sort', 'desc')->get()->map(function ($row) use ($permissions) {
                $permissions->add($row);
            });
        });

        return $permissions->unique('name');
    }

    public function hasPermissionNames()
    {
        return $this->hasPermissions()->map(function ($permission) {
            return $permission->name;
        });
    }

    public function hasAccess($permission): bool
    {
        if ($this->active != 1) {
            return false;
        }
        return $this->hasPermissionNames()->contains($permission);
    }
}
