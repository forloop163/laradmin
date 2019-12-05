<?php

namespace App\Http\Controllers\System;

use App\Business\System\Role as RoleBusiness;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\System\Role as RoleModel;

class RoleController extends BaseController
{
    public function __construct(RoleModel $model)
    {
        $this->query = $model;

        $this->handles = [
            'name' => function ($query, $value) {
                return $query->where('name', 'like', '%' . $value . '%');
            },
            'active' => function ($query, $value) {
                return $query->where('active', $value);
            }
        ];

        $this->withes['index'] = ['permissions'];
        $this->withes['show'] = ['permissions'];

        $this->fields['store'] = ['name', 'desc', 'active'];
        $this->fields['update'] = ['name', 'desc', 'active'];
        $this->fields['sortable'] = ['id'];
    }


    public function roles(Request $request)
    {
        $business = new RoleBusiness;
        $roles = $business->roles();
        return $this->success($roles);
    }

    public function setRolePermissions(Request $request, $id)
    {
        $data = $request->get('permissions', []);

        $business = new RoleBusiness($this->find($this->query, $id));
        $result = $business->setPermssions($data);
        return $this->success($result);
    }
}
