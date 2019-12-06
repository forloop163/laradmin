<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\BaseController;
use App\Models\System\ContentTables;

class Index extends BaseController
{
    public function __construct(ContentTables $model)
    {
        $this->query = $model;

        $this->handles = [
            'name' => function ($query, $value, $request) {
                return $query->where('name', 'like', '%' . $value . '%');
            },
            'table_name' => function ($query, $value, $requert) {
                return $query->where('table_name', 'like', '%' . $value . '%');
            },
        ];

        $this->withes['index'] = ['roles'];

        $this->fields['store'] = ['name', 'table_name'];
        $this->fields['update'] = ['name', 'table_name'];
        $this->fields['sortable'] = ['id', 'created_at', 'updated_at'];
    }
}
