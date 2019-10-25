<?php
namespace App\Business\System;

use Illuminate\Database\Eloquent\Model;

class Role
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function roles()
    {
        return $this->model->where('active', 1)->get(['id', 'name']);
    }

    public function setPermssions($data)
    {
        $this->model->permissions()->detach();
        return $this->model->permissions()->attach($data);
    }
}
