<?php

namespace App\Http\Controllers;

use App\Business\System\Auth as AuthBusiness;
use App\Business\System\Log as LogBusiness;
use App\Helpers\ApiResponseHelper;
use App\Helpers\BusinessHelper;
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

    protected $validateRules = [
        'create'  => [],
        'update' => []
    ];

    protected $validateMessage = [
        'create'  => [],
        'update' => []
    ];

    public function index(Request $request)
    {
        $businessHelper = new BusinessHelper($request);
        $data = $businessHelper->setQuery($this->query)
            ->setWith($this->withes['index'])
            ->setSearchHandle($this->handles)
            ->index();

        return $this->success($data);
    }

    public function show(Request $request, $id)
    {
        $businessHelper = new BusinessHelper();
        $data = $businessHelper->setWith($this->withes['show'])->setQuery($this->query)->show($id);

        return $this->success($data);
    }

    public function store(Request $request)
    {
        $businessHelper = new BusinessHelper($request);
        $create = $businessHelper->setQuery($this->query)->store($this->fields['store']);

        $this->success($create);
    }

    public function update(Request $request, $id)
    {
        $businessHelper = new BusinessHelper($request);
        $update = $businessHelper->setQuery($this->query)->update($id, $this->fields['update']);

        return $this->success($update);
    }

    public function destroy(Request $request, $id)
    {
        $businessHelper = new BusinessHelper();
        $destory = $businessHelper->setQuery($this->query)->destroy($id);

        return $this->success($destory);
    }

    public function reset(Request $request, $id)
    {
        $businessHelper = new BusinessHelper();
        $reset = $businessHelper->setQuery($this->query)->reset($id);

        return $this->success($reset);
    }

    public function freeze(Request $request, $id)
    {
        $businessHelper = new BusinessHelper();
        $freeze = $businessHelper->setQuery($this->query)->freeze($id);

        return $this->success($freeze);
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
}
