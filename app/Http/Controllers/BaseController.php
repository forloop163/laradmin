<?php

namespace App\Http\Controllers;

use App\Business\System\Auth as AuthBusiness;
use App\Business\System\Log as LogBusiness;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;

class BaseController
{
    use ApiResponseHelper;

    protected $query = null;

    protected $withes = [
        'index' => [],
        'show' => []
    ];

    protected $handles = [];

    protected $fields = [
        'store' => [],
        'update' => [],
        'export' => [],
        'import' => [],
        'sortable' => [],
    ];

    public function index(Request $request)
    {
        $withes = $this->withes['index'];
        $pageSize = $request->get('pageSize', 20);
        if (!is_numeric($pageSize)) {
            $pageSize = 20;
        }

        $query = $this->performSearch($request, $this->handles);

        $query = $query->with($withes)->select('*');

        // 处理排序
        $sort = $request->get('sort', []);
        if (!is_array($sort)) {
            $sort = json_decode($sort, true);
        }

        $prop = $sort['prop'] ?? '';
        $order = $sort['order'] ?? 'asc';
        $order = $order === 'ascending' ? 'asc' : 'desc';
        if (in_array($prop, $this->fields['sortable'])) {
            $query = $query->orderBy($prop, $order);
        }

        $results = $query->paginate($pageSize);

        $results->map(function ($item) {
            if (isset($item->query)) {
                return $item->query = empty($item->query) ? null : $item->query;
            }
        });
        return $this->performIndex($results);
    }

    public function performIndex($results)
    {
        return $this->success($results);
    }

    function performSearch($request, $handles = [], $params = null)
    {
        $query = $this->query;
        if (empty($handles)) {
            return $query;
        }
        $keys = array_keys($handles);

        $params = $params ?: $request->only($keys);
        foreach ($params as $key => $value) {
            if (trim($value) === '') {
                continue;
            }
            if (isset($handles[$key]) && is_callable($handles[$key])) {
                $query = $handles[$key]($query, $value, $params);
            }
        }
        return $query;
    }

    public function show(Request $request, $id)
    {
        $withes = $this->withes['show'];
        $row = $this->query->with($withes)->find($id);
        if (!$row) {
            return $this->notFond();
        }

        return $this->performShow($row);
    }

    public function performShow($row)
    {
        return $this->success($row);
    }

    public function store(Request $request)
    {
        $fields = $this->fields['store'];
        $post = $request->only($fields);

        return $this->performStore($post);
    }

    public function performStore($data)
    {
        $create = $this->query->create($data);
        $this->writeLog($create, 'store', $data);

        return $this->success($create);
    }

    public function update(Request $request, $id)
    {
        $fields = $this->fields['update'];
        $post = $request->only($fields);

        $find = $this->find($this->query, $id);

        return $this->performUpdate($find, $post);
    }

    public function performUpdate($model, $data)
    {
        $model->fill($data);
        $model->save();
        $this->writeLog($model, 'update', $data);

        return $this->success($model);
    }

    public function destroy(Request $request, $id)
    {
        $find = $this->find($this->query, $id);

        return $this->performDestroy($find);
    }

    public function performDestroy($model)
    {
        $model->delete();
        $this->writeLog($model, 'delete');

        return $this->success($model);
    }

    public function reset(Request $request, $id)
    {
        $find = $this->find($this->query, $id);

        return $this->performReset($find);
    }

    public function performReset($model)
    {
        $model->active = 1;
        $model->save();
        $this->writeLog($model, 'reset');

        return $this->success($model);
    }

    public function freeze(Request $request, $id)
    {
        $find = $this->find($this->query, $id);

        return $this->performFreeze($find);
    }

    public function performFreeze($model)
    {
        $model->active = 0;
        $model->save();
        $this->writeLog($model, 'freeze');

        return $this->success($model);
    }


    public function writeLog($model, $action, $data = [], $log = ''): void
    {
        $modelClass = $this->getClass($model);
        $user = $this->getUser();

        $actions = [
            'store' => '创建',
            'update' => '修改',
            'delete' => '删除',
            'reset' => '恢复',
            'freeze' => '冻结'
        ];

        if (empty($log)) {
            $log = "{$user->username} " . ($actions[$action] ?? $action) . " {$modelClass}";
        }

        LogBusiness::write([
            'entity_id' => $model->id,
            'user_id' => $user->id,
            'action' => $action,
            'data' => $data,
            'model' => $modelClass,
            'log' => $log
        ]);
    }

    protected function find($model, $id)
    {
        $find = $model->find($id);
        if (empty($find)) {
            throw new \Exception('Not found');
        }
        return $find;
    }

    protected function getUser()
    {
        $authBusiness = new AuthBusiness();
        $user = $authBusiness->authUser();

        if (!$user) {
            abort(411, '登陆已过期,请重新登陆');
        }
        return $user;
    }

    protected function getClass($object): string
    {
        return get_class($object);
    }
}
