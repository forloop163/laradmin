<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentTables extends Model
{
    use SoftDeletes;

    protected $table = 'content_tables';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function columns()
    {
        return $this->hasMany('App\Models\System\ContentTableColumns', 'content_table_id', 'content_table_id');
    }
}
