<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\BaseController;
use App\Models\System\ContentTables as ContentTableModel;

class ContentController extends BaseController
{
    public function __construct(ContentTableModel $model)
    {
        $this->query = $model;

        $this->handles = [
            'name' => function ($query, $value) {
                return $query->where('name', 'like', '%' . $value . '%');
            },
            'table_name' => function ($query, $value) {
                return $query->where('table_name', 'like', '%' . $value . '%');
            },
        ];

        $this->withes['show'] = ['columns'];
    }
}
