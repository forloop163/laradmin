<?php

namespace App\Http\Controllers\System;

use App\Business\System\Permission;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\System\Permission as PermissionModel;

class PermissionController extends BaseController
{
    public function __construct(PermissionModel $model)
    {
        $this->query = $model;

        $this->fields['store'] = ['name', 'label', 'path', 'meta', 'display', 'component', 'parent', 'redirect'];
        $this->fields['update'] = ['name', 'label', 'path', 'meta', 'display', 'component', 'parent', 'redirect'];
    }

    /**
     * index
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $business = new Permission;

        return $this->success($business->tree());
    }

    /**
     * 权限节点拖拽
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function nodeDrop(Request $request)
    {
        $params = $request->only(['draggingNode', 'dropNode', 'dropType']);

        $business = new Permission;
        $business->nodeDrop($params['draggingNode'], $params['dropNode'], $params['dropType']);

        return $this->success([]);
    }

}
