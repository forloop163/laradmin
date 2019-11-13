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
            'username' => function ($query, $value, $request) {
                return $query->where('username', 'like', '%' . $value . '%');
            },
            'email' => function ($query, $value, $requert) {
                return $query->where('email', 'like', '%' . $value . '%');
            },
            'mobile' => function ($query, $value, $requert) {
                return $query->where('mobile', $value);
            },
            'active' => function ($query, $value, $requert) {
                return $query->where('active', $value);
            },
            'is_admin' => function ($query, $value, $requert) {
                return $query->where('is_admin', $value);
            },
        ];

        $this->withes['index'] = ['roles'];

        $this->fields['store'] = ['username', 'email', 'mobile', 'active', 'password', 'roles'];
        $this->fields['update'] = ['username', 'email', 'mobile', 'active', 'password', 'roles'];
        $this->fields['sortable'] = ['id', 'created_at', 'updated_at', 'last_login'];
    }

    public function performStore($data)
    {
        $business = new UserBusiness;
        $create = $business->create($data);
        $this->writeLog($create, 'store', $data);

        return $this->success($create);
    }

    public function performUpdate($model, $data)
    {
        $business = new UserBusiness;
        $business->update($data);
        $this->writeLog($model, 'update', $data);

        return $this->success($model);
    }
}
