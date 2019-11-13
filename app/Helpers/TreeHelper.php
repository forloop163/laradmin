<?php
namespace App\Helpers;

use Illuminate\Support\Arr;

class TreeHelper
{
    /**
     * 主键名称.
     *
     * @var string
     */
    private static $primary = 'id';
    /**
     * 父键名称.
     *
     * @var string
     */
    private static $parentId = 'parent';
    /**
     * 子节点名称.
     *
     * @var string
     */
    private static $child = 'children';

    private static $childNodes = [];

    protected static $sort = null;

    /**
     * 修改主键名称、父键名称、子节点名称.
     *
     * @param string $primary
     * @param string $parentId
     * @param string $child
     */
    public static function setConfig($primary = '', $parentId = '', $child = '')
    {
        if (!empty($primary)) {
            self::$primary = $primary;
        }
        if (!empty($parentId)) {
            self::$parentId = $parentId;
        }
        if (!empty($child)) {
            self::$child = $child;
        }
    }

    public static function setSort($sort)
    {
        self::$sort = $sort;
    }

    /**
     * 生成Tree.
     *
     * @param array $data
     * @param int $index
     * @return array
     */
    public static function makeTree(array &$data, $index = null)
    {
        $children = self::findChild($data, $index);
        if (empty($children)) {
            return $children;
        }
        foreach ($children as $k => &$v) {
            if (empty($data)) {
                break;
            }
            $child = self::makeTree($data, $v[self::$primary]);
            if (!empty($child)) {
                $v[self::$child] = $child;
            } else {
                self::$childNodes[] = $v[self::$primary];
                $v[self::$child] = [];
            }
        }
        unset($v);
        return self::sort($children);
    }

    public static function sort($data)
    {
        if (!self::$sort) {
            return $data;
        }

        $data = Arr::sort($data, function ($value) {
            return (int) $value[self::$sort['sort']];
        });

        if (self::$sort['by'] === 'desc') {
            return array_reverse($data);
        }
        return $data;
    }

    /**
     * 查找子类.
     *
     * @param array $data
     * @param number $index
     * @return array
     */
    public static function findChild(&$data, $index)
    {
        $children = [];
        foreach ($data as $k => $v) {
            if ($v[self::$parentId] === $index) {
                $children[] = $v;
                unset($v);
            }
        }

        return self::sort($children);
    }

    public static function getChildNodes()
    {
        return self::$childNodes;
    }
}
