<?php

namespace App\Business\System;

use App\Models\System\Log as LogModel;

class Log
{
    protected $modelClass = LogModel::class;

    public static function write($data)
    {
        return LogModel::query()->create($data);
    }
}
