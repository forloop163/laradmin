<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use App\Exceptions\BusinessException;

class BusinessHelper
{
    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var string
     */
    protected $sortBy = 'id';

    /**
     * @var string
     */
    protected $order = 'asc';

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var int
     */
    protected $pageSize = 20;

    /**
     * @var array
     */
    protected $searchHandles = [];

    /**
     * @var Request|null
     */
    protected $request;

    /**
     * @var string | array
     */
    protected $select = '*';

    /**
     * BusinessHelper constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @param $relactions
     * @return $this
     */
    public function setWith($relactions)
    {
        $this->with = $relactions;
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function setSortBy($field)
    {
        $this->sortBy = $field;
        return $this;
    }

    /**
     * @param $queryBuilder
     * @return $this
     */
    public function setQuery($queryBuilder)
    {
        $this->query = $queryBuilder;
        return $this;
    }

    /**
     * @param $size
     * @return $this
     */
    public function setPageSize($size)
    {
        $this->pageSize = $size;
        return $this;
    }

    /**
     * @param $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function setSearchHandle($searchHandles)
    {
        $this->searchHandles = $searchHandles;
        return $this;
    }

    /**
     * @return Builder
     * @throws BusinessException
     */
    public function getQuery()
    {
        if (!$this->query) {
            throw new BusinessException('Please set build-query');
        }
        return $this->query;
    }

    /**
     * @return BusinessHelper
     */
    protected function setIndexParams()
    {
        $size = (int) $this->request->get('pageSize', 20);
        $sort = $this->request->get('sort', []);
        if (!is_array($sort)) {
            $sort = json_decode($sort, true);
        }

        $sortBy = $sort['prop'] ?? 'id';
        $order = $sort['order'] ?? 'asc';
        $order = $order === 'ascending' ? 'asc' : 'desc';

        return $this->setPageSize($size)->setSortBy($sortBy)->setOrder($order);
    }

    /**
     * @return mixed
     * @throws BusinessException
     */
    protected function resolveSearchHandels()
    {
        $query = $this->getQuery();

        if (empty($this->searchHandles)) {
            return $query;
        }

        $keys = array_keys($this->searchHandles);
        $params = $this->request->only($keys);

        foreach ($params as $key => $value) {
            if (trim($value) === '') {
                continue;
            }
            if (isset($this->searchHandles[$key]) && is_callable($this->searchHandles[$key])) {
                $query = $this->searchHandles[$key]($query, $value);
            }
        }

        return $query;
    }

    /**
     * @return mixed
     * @throws BusinessException
     */
    public function index()
    {
        return $this->setIndexParams()
            ->resolveSearchHandels()
            ->with($this->with)
            ->select($this->select)
            ->orderBy($this->sortBy, $this->order)
            ->paginate($this->pageSize);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|Model[]|null
     * @throws BusinessException
     */
    public function show($id)
    {
        $row = $this->getQuery()->with($this->with)->find($id);
        if (!$row) {
            throw new BusinessException('Not Found');
        }

        return $row;
    }

    /**
     * @param $storeFields
     * @return mixed
     * @throws BusinessException
     */
    public function store($storeFields)
    {
        $requestOnly = $this->request->only($storeFields);
        return $this->getQuery()->insert($requestOnly);
    }

    /**
     * @param $id
     * @param $updateFields
     * @return mixed
     * @throws BusinessException
     */
    public function update($id, $updateFields)
    {
        $row = $this->getQuery()->find($id);
        if (!$row) {
            throw new BusinessException('Not Found');
        }

        $update = $this->request->only($updateFields);
        $row->fill($update);
        return $row->save();
    }

    /**
     * @param $id
     * @return int
     * @throws BusinessException
     */
    public function destroy($id)
    {
        return $this->getQuery()->delete($id);
    }

    /**
     * @param $id
     * @return int
     * @throws BusinessException
     */
    public function reset($id)
    {
        return $this->getQuery()->where('id', $id)->update(['active' => 1]);
    }

    /**
     * @param $id
     * @return int
     * @throws BusinessException
     */
    public function freeze($id)
    {
        return $this->getQuery()->where('id', $id)->update(['active' => 0]);
    }
}
