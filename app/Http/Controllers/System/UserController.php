<?php

namespace App\Http\Controllers\System;

use App\Business\System\User as UserBusiness;
use App\Http\Controllers\BaseController;
use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                return $query->where('email', $value);
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
        $business = new UserBusiness($this->query);
        $create = $business->create($data);
        $this->writeLog($create, 'store', $data);

        return $this->success($create);
    }

    public function performUpdate($model, $data)
    {
        $business = new UserBusiness($model);
        $business->update($data);
        $this->writeLog($model, 'update', $data);

        return $this->success($model);
    }

    public function show(Request $request, $id)
    {
        $user = $this->getUser();

        $business = new UserBusiness($this->query);
        return $this->success($business->loginUserInfoFromCache($user));
    }
}
