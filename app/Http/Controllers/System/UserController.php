<?php

namespace App\Http\Controllers\System;

use App\Business\System\User as UserBusiness;
use App\Http\Controllers\BaseController;
use App\Models\System\User as UserModel;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct(UserModel $model)
    {
        $this->query = $model;

        $this->handles = [
            'username' => function ($query, $value) {
                return $query->where('username', 'like', '%' . $value . '%');
            },
            'email' => function ($query, $value) {
                return $query->where('email', 'like', '%' . $value . '%');
            },
            'mobile' => function ($query, $value) {
                return $query->where('mobile', $value);
            },
            'active' => function ($query, $value) {
                return $query->where('active', $value);
            },
            'is_admin' => function ($query, $value) {
                return $query->where('is_admin', $value);
            },
        ];

        $this->withes['index'] = ['roles'];

        $this->fields['store'] = ['username', 'email', 'mobile', 'active', 'password', 'roles'];
        $this->fields['update'] = ['username', 'email', 'mobile', 'active', 'password', 'roles'];
        $this->fields['sortable'] = ['id', 'created_at', 'updated_at', 'last_login'];
    }

    public function store(Request $request)
    {
        $business = new UserBusiness;
        $data = $request->only($this->fields['store']);
        $create = $business->create($data);

        return $this->success($create);
    }

    public function update(Request $request, $id)
    {
        $business = new UserBusiness;
        $data = $request->only($this->fields['update']);
        $update = $business->update($data, $id);

        return $this->success($update);
    }
}
