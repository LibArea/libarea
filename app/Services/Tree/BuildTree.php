<?php

declare(strict_types=1);

namespace App\Services\Tree;

class BuildTree
{
    public static function index($group, array $flatList, string $type = 'comment')
    {
        $grouped = [];
        foreach ($flatList as $node) {
            $grouped[$node[$type . '_parent_id']][] = $node;
        }

        $siblings = [];
        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $type) {
            if ($siblings) {
                foreach ($siblings as $k => $sibling) {
                    $id = $sibling[$type . '_id'];
                    if (isset($grouped[$id])) {
                        $sibling['children'] = $fnBuilder($grouped[$id]);
                    }
                    $siblings[$k] = $sibling;
                }
            }
            return $siblings;
        };

		$tree = [];
        if (isset($grouped[$group])) {
            $tree = $fnBuilder($grouped[$group]);
        }

        return $tree;
    }
}
