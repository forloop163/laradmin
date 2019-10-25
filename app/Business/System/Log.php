<?php

namespace App\Business\System;


class Log
{
    public static function write($data)
    {
        return \App\Models\Log::query()->create($data);
    }
}
