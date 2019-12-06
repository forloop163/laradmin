<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $table = 'system_log';

    protected $fillable = ['user_id', 'path', 'action', 'ip', 'data'];

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\System\User', 'id', 'user_id');
    }

    // 覆盖 model 方法
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
