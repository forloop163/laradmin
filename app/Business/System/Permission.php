<?php
namespace App\Business\System;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\TreeHelper;

class Permission
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * 权限树
     * @return array
     */
    public function tree()
    {
        $data = $this->model->orderBy('sort')->get()->toArray();
        return TreeHelper::makeTree($data, 0);
    }

    /**
     * 菜单树
     * @return array
     */
    public function meauTree()
    {
        $permissions = $this->model->with('roles')->where('is_api', 0)
            ->orderBy('sort')->get()->toArray();

        foreach ($permissions as &$permission) {
            $permission['meta']['roles'] = collect($permission['roles'])->pluck('name')->toArray();
        }
        return TreeHelper::makeTree($permissions, 0);
    }


    public function getAllPermissionName() :array
    {
        return $this->model->pluck('name')->toArray();
    }

    /**
     * 节点拖拽排序
     * @param $draggingNode
     * @param $dropNode
     * @param string $type
     * @throws \Exception
     */
    public function nodeDrop($draggingNode, $dropNode, $type = 'before')
    {
        if ($type == 'before' || $type == 'after') {
            $parentId = $dropNode['parent'];
            // 排序
            $children = clone ($this->model)->where('parent', $parentId)->orderBy('sort', 'asc')->get();
            $tmpIds = [];
            foreach ($children as $child) {
                if ($child['id'] == $dropNode['id']) {
                    if ($type == 'before') {
                        $tmpIds[] = $draggingNode['id'];
                        $tmpIds[] = $child['id'];
                    }
                    if ($type == 'after') {
                        $tmpIds[] = $child['id'];
                        $tmpIds[] = $draggingNode['id'];
                    }
                }
            }
            $this->updateSorts($tmpIds);
        } elseif ($type == 'inner') {
            $parentId = $dropNode['id'];
        }

        $this->updateParent($draggingNode['id'], $parentId);
    }

    /**
     * 更新当前记录父节点
     * @param $id
     * @param int $parent
     * @return mixed
     * @throws \Exception
     */
    public function updateParent($id, $parent = 0)
    {
        $row = $this->model->find($id);
        if (!$row) {
            throw new \Exception('该记录不存在');
        }
        $row->parent = $parent;
        return $row->save();
    }

    /**
     * 更新排序
     * @param $ids
     */
    public function updateSorts($ids)
    {
        foreach ($ids as $key => $id) {
            $this->model->where('id', $id)->update(['sort' => $key + 1]);
        }
    }
}
