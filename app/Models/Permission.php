<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $table = 'permissions';

    protected $fillable = [];

    protected $guarded = ['id'];

    protected $casts = [
        'parent' => 'int',
        'meta' => 'array',
        'is_api' => 'int'
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_permission', 'permission_id', 'role_id');
    }

    // 覆盖 model 方法
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
