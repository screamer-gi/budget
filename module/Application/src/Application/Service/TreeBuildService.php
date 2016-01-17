<?php
namespace Application\Service;


class TreeBuildService
{
    private function makeTree(&$list, $index = 0)
    {
        if (empty($list[$index])) {
            return array();
        }
        $tree = array();
        foreach ($list[$index] as $f) {
            $f['children'] = $this->makeTree($list, $f['id']);
            if (!$f['children']) {
                $f['leaf'] = true;
            }
            $tree[] = $f;
        }
        return $tree;
    }

    public function getTree(array $source)
    {
        $list = array();
        foreach ($source as $rec) {
            $parent = $rec->parent ? $rec->parent->id : 0;

            if (empty($list[$parent])) {
                $list[$parent] = array();
            }
            $list[$parent][] = array(
                'id' => $rec->id,
                //'type' => 'department',
                'text' => $rec->name,
                'leaf' => false,
                'expanded' => true,
            );
        }

        $tree = $this->makeTree($list);
        return $tree;
    }
}