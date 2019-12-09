<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class ITreeHelper
{
    protected $index = 'id';

    protected $parent = 'parent_id';

    protected $sortBy;

    protected $order = 'desc';

    protected $parentDefault = 0;

    protected $containsEmptyChildren = true;

    protected $children = 'children';

    public function __construct(array $config)
    {
        if (isset($config['index'])) {
            $this->index = $config['index'];
        }
        if (isset($config['parent'])) {
            $this->parent = $config['parent'];
        }
        if (isset($config['sort_by'])) {
            $this->sortBy = $config['sort_by'];
        }
        if (isset($config['order'])) {
            $this->order = $config['order'];
        }
        // 默认父级ID
        if (isset($config['parent_default'])) {
            $this->parentDefault = $config['parent_default'];
        }
        if (isset($config['contains_empty'])) {
            $this->containsEmptyChildren = $config['contains_empty'];
        }
    }

    public function makeTree(array $data, $parent = null)
    {
        $children = $this->getChildren($data, $parent ?: $this->parentDefault);

        foreach ($children as &$child) {
            $childrenValues = $this->getChildren($data, $child[$this->index]);
            if (empty($childrenValues) && !$this->containsEmptyChildren) {
                continue;
            } else {
                $child[$this->children] = $childrenValues;
                foreach ($childrenValues as $item) {
                    $this->makeTree($childrenValues, $item[$this->index]);
                }
            }
        }

        return $children;
    }

    protected function getChildren(&$data, $parent)
    {
        $children = [];
        foreach ($data as &$value) {
            if ($value[$this->parent] == $parent) {
                $children[] = $value;
                unset($value);
            }
        }

        return $this->sortData($children);
    }

    protected function sortData($data)
    {
        if (!$this->sortBy || empty($data)) {
            return $data;
        }

        $data = Arr::sort($data, function ($value) {
            return (int)$value[$this->sortBy];
        });

        if ($this->order === 'desc') {
            return array_reverse($data);
        }
        return $data;
    }
}
