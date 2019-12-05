<?php

namespace App\Business\System;

use App\Business\BaseBusiness;
use App\Business\System\Permission as PermissionsBusiness;
use App\Models\System\User as UserModel;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\BusinessException;
use Illuminate\Validation\Rule;

class User extends BaseBusiness
{
    protected $modelClass = UserModel::class;

    public function create(array $data)
    {
        $rules = [
            'username' => 'required|unique:users|max:30|min:6',
            'mobile' => 'required|max:20',
            'password' => 'required|min:6',
            'email' => 'required|email'
        ];
        $messages = [
            'username.required' => '请输入用户名(最少6位)',
            'username.min' => '用户名最少6位',
            'username.unique' => '用户名已存在',
            'username.max' => '用户名最长30位',
            'mobile.required' => '请输入手机号码',
            'mobile.max' => '手机号码最长20位',
            'password.required' => '请输入密码',
            'password.min' => '请输入密码(最少6位)',
            'email.required' => '请输入正确的邮箱号码',
            'email.email' => '请输入正确的邮箱号码'
        ];

        $this->validate($data, $rules, $messages);

        $data['password'] = bcrypt($data['password']);
        $roles = $data['roles'];
        unset($data['roles']);

        $create = $this->model->create($data);
        $create->roles()->attach($roles);

        return $create;
    }

    private function validate($data, $rules, $messages)
    {
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            throw new BusinessException($validator->errors()->first());
        }
    }

    public function update(array $data, $id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            throw new BusinessException('Not Found');
        }

        $rules = [
            'username' => [
                'required',
                'max:30',
                'min:6',
                Rule::unique('users')->ignore($id),
            ],
            'mobile' => 'required|max:20',
            'password' => 'min:6',
            'email' => 'required|email'
        ];

        $messages = [
            'username.required' => '请输入用户名(最少6位)',
            'username.min' => '用户名最少6位',
            'username.unique' => '用户名已存在',
            'username.max' => '用户名最长30位',
            'mobile.required' => '请输入手机号码',
            'mobile.max' => '手机号码最长20位',
            'password.min' => '请输入密码(最少6位)',
            'email.required' => '请输入正确的邮箱号码',
            'email.email' => '请输入正确的邮箱号码'
        ];

        $this->validate($data, $rules, $messages);

        $roles = $data['roles'];
        unset($data['roles']);
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $model->fill($data);

        $model->roles()->detach();
        $model->roles()->attach($roles);

        return $model->save();
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
