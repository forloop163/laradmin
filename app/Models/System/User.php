<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'mobile', 'email', 'password', 'remember_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function roles()
    {
        return $this->belongsToMany('App\Models\System\Role', 'user_role', 'user_id', 'role_id');
    }

    public function hasAccess($permission)
    {
        $permissions = [];

        $this->roles->map(function ($role) use (&$permissions) {
            $role->permissions->map(function ($row) use (&$permissions) {
                $permissions[] = $row->name;
            });
        });

        $permissions = array_unique($permissions);
        return in_array($permission, $permissions);
    }
}

