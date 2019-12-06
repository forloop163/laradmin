<?php

namespace App\Business;

class ModelsObserver
{
    public function callback()
    {
    }

    public function printSql()
    {
        \DB::listen(function ($query) {
            $sql = $query->sql;
            foreach ($query->bindings as $i => $item) {
                $sql = preg_replace('/\?/', "'$item'", $sql, 1);
            }
            logger("$sql [$query->time]");
        });
    }

    public function writeRequestLog()
    {

    }
}
