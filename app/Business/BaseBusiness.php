<?php
namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class BaseBusiness
{
    protected $modelClass = '';

    protected $model;

    public function __construct(Model $model = null)
    {
        if (!$model && !empty($this->modelClass)) {
            $this->model = new $this->modelClass;
        } else {
            $this->model = $model;
        }
    }
}
