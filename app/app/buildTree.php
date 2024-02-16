<?php
namespace App;

class buildTree
{
    function Tree($categories)
    {
        $tree = [];

        foreach ($categories as $category) {
            $sub = $category->sub;

            if ($sub->isNotEmpty()) {
                $category->sub = $this->Tree($sub);
            }
            else
                $category->sub =null;

            $tree[] = $category;
        }

        return $tree;
    }

 
}