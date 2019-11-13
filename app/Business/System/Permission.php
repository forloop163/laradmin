<?php
namespace App\Business\System;

use App\Business\BaseBusiness;
use App\Helpers\TreeHelper;
use App\Models\System\Permission as PermissionModel;

class Permission extends BaseBusiness
{
    protected $modelClass = PermissionModel::class;

    /**
     * 权限树
     * @return array
     */
    public function tree()
    {
        $data = $this->model->orderBy('sort', 'desc')->get()->toArray();
        TreeHelper::setSort(['sort'=> 'sort', 'by'=> 'desc']);
        return TreeHelper::makeTree($data, 0);
    }

    /**
     * 菜单树
     * @return array
     */
    public function meauTree($user)
    {
        $withoutApi = true;
        $permissions = $user->hasPermissions($withoutApi)->toArray();
        TreeHelper::setSort(['sort'=> 'sort', 'by'=> 'desc']);
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
            $children = $this->model->where('parent', $parentId)->orderBy('sort', 'desc')->get();

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
                } else if ($child['id'] == $draggingNode['id']) {
                    continue;
                } else {
                    $tmpIds[] = $child['id'];
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
        $count = count($ids);
        foreach ($ids as $key => $id) {
            $this->model->where('id', $id)->update(['sort' => $count - $key]);
        }
    }
}
