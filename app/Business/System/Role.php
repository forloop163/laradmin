<?php
namespace App\Business\System;

use App\Business\BaseBusiness;
use App\Models\System\Role as RoleModel;

class Role extends BaseBusiness
{
    protected $modelClass =RoleModel::class;

    public function roles()
    {
        return $this->model->where('active', 1)->get(['id', 'name']);
    }

    public function setPermssions($id, $data)
    {
        $row = $this->model->find($id);
        $row->permissions()->detach();
        return $row->permissions()->attach($data);
    }
}
