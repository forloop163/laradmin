<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $table = 'system_log';

    protected $fillable = ['entity_id', 'user_id', 'log', 'action', 'model', 'data'];

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array'
    ];

    // 覆盖 model 方法
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
