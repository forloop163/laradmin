<?php

namespace App\Business\Content;

use App\Models\System\ContentTables as ContentTablesModel;

class Table
{
    public function tableWithColumns($tableId)
    {
        $table = ContentTablesModel::query()->with('columns')
            ->find($tableId);
        if (!$table) {
            abort('404');
        }

        return $table;
    }
}
