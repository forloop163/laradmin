<?php

namespace App\Business\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Permission as PermissionModel;
use App\Business\System\Permission as PermissionsBusiness;

class User
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        $data['password'] = encrypt($data['password']);
        $roles = $data['roles'];
        unset($data['roles']);

        $create = $this->model->create($data);
        $create->roles()->attach($roles);

        return $create;
    }

    public function update(array $data)
    {
        $roles = $data['roles'];
        unset($data['roles']);
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = $this->encryptPassword($data['password']);
        }
        $this->model->fill($data);

        $this->model->roles()->detach();
        $this->model->roles()->attach($roles);

        return $this->model->save();
    }

    /**
     * 用户登陆信息缓存
     * @param $user
     * @return mixed
     */
    public function loginUserInfoFromCache($user)
    {
        if (config('app.env') == 'local') {
            return $this->LoginUserInfo($user);
        }

        $cacheKey = 'laradmin-' . $user->id . '-login-user';
        return Cache::remember($cacheKey, 300 ,function () use ($user) {
            return $this->LoginUserInfo($user);
        });
    }


    /**
     * 用户登陆信息
     * @return mixed
     */
    public function LoginUserInfo($user)
    {
        $permissionBusiness = new PermissionsBusiness(new PermissionModel);
        $permissions =$permissionBusiness->meauTree();

        // TODO 过滤 active = 1， 有效的角色
        $roles = $user->roles()->pluck('name')->toArray();

        $res = [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions
        ];

        $this->loginedCallBack($user);
        return $res;
    }

    /**
     * 登陆成功后回调
     * @param $user
     * @return mixed
     */
    public function loginedCallBack($user)
    {
        $user->last_login = Carbon::now();
        return $user->save();
    }

    /**
     * 获取remember_token
     * @return string
     */
    public function getApiToken($user)
    {
        return md5($user->id . Str::random(10)) . Str::random(32);
    }

    public function resetApiToken()
    {
        $effective = config('laradmin.token_time');
        $deadline = Carbon::addHours(-1 * $effective);
        $this->model->where('last_login', '<=', $deadline)->chuck(500, function ($rows) {
            $rows->map(function ($item) {
                $item->api_token = $this->getApiToken($item);
                $item->save();
            });
        });
    }

    /**
     * 加密密码
     * @param $password
     * @return string
     */
    public function encryptPassword($password)
    {
        return encrypt($password);
    }
}
