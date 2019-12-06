<?php

namespace App\Http\Controllers\System;

use App\Helpers\BusinessHelper;
use App\Http\Controllers\Controller;
use App\Models\System\Log as LogModel;
use Illuminate\Http\Request;

class LogController extends Controller
{
    protected $query;

    protected $with;

    public function __construct(LogModel $log)
    {
        $this->query = $log;
    }

    public function index(Request $request)
    {
        $searchHandles = [
            'name' => function ($query, $value) {
                return $query->whereHas('user', function ($q) use ($value) {
                    return $q->where('username', 'like', '%' . $value . '%');
                });
            },
        ];
        $businessHelper = new BusinessHelper($request);
        $data = $businessHelper
            ->setWith(['user' => function($query) {
                return $query->select(['username', 'email', 'id']);
            }])
            ->setSearchHandle($searchHandles)
            ->setQuery($this->query)
            ->setSortBy('id')
            ->setOrder('desc')
            ->index();

        return $this->success($data);
    }
}
