<?php

namespace App\Business\System;

use App\Business\BaseBusiness;
use App\Business\System\Permission as PermissionsBusiness;
use App\Models\System\User as UserModel;

class User extends BaseBusiness
{
    protected $modelClass = UserModel::class;

    public function create(array $data)
    {
        $has = $this->model->where('username', $data['username'] ?? '')->orWhere('email', $data['username'] ?? '')->first();
        if ($has) {
            throw new \Exception('用户名或者邮箱已存在');
        }

        $data['password'] = bcrypt($data['password']);
        $roles = $data['roles'];
        unset($data['roles']);

        $create = $this->model->create($data);
        $create->roles()->attach($roles);

        return $create;
    }

    public function update(array $data)
    {
        $has = $this->model->where('id', '<>', $this->model->id)
            ->where('username', $data['username'] ?? '')->orWhere('email', $data['username'] ?? '')->first();
        if ($has) {
            throw new \Exception('用户名或者邮箱已存在');
        }
        $roles = $data['roles'];
        unset($data['roles']);
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $this->model->fill($data);

        $this->model->roles()->detach();
        $this->model->roles()->attach($roles);

        return $this->model->save();
    }


    /**
     * 用户登陆信息
     * @return mixed
     */
    public function LoginUserInfo($user)
    {
        $permissionBusiness = new PermissionsBusiness;
        $permissions = $permissionBusiness->meauTree($user);

        return [
            'user' => ['username'=>$user->username],
            'permissions' => $permissions
        ];
    }


    /**
     * 加密密码
     * @param $password
     * @return string
     */
    public function encryptPassword($password)
    {
        return bcrypt($password);
    }
}
