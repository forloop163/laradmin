<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentTableColumns extends Model
{
    use SoftDeletes;

    protected $table = 'content_table_columns';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function columns()
    {
        return $this->belongsTo('App\Models\System\ContentTables');
    }
}
