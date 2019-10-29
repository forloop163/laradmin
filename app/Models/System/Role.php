<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany('App\Models\System\User', 'users', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\System\Permission', 'role_permission', 'role_id', 'permission_id')->orderBy('sort');
    }
}
