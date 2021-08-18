<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class TreeService
{
    public function getTree(Model $model, string $viewPath)
    {
        $tree = "<ul class='tree'>";
        $startCounter = 1;
        $model->select('id', 'branch_id', 'name_ar', 'name_en', 'parent_id')
            ->whereNull('parent_id')
            ->with('branch')
            ->get()
            ->each(function ($item) use (&$tree, &$startCounter, $viewPath) {
                $tree .= view($viewPath . '.tree-li', ['child' => $item, 'counter' => $startCounter])->render();
                $tree .= '<ul style="display:none" id="ul-tree-' . $item->id . '">';
                $this->getMyChildren($item, $tree, $startCounter, $viewPath);
                $tree .= '</ul></li>';
                $startCounter++;
            });
        $tree .= '</ul>';
        return $tree;
    }

    private function getMyChildren(Model $model, &$htmlCode, $depth, string $viewPath)
    {
        $counter = 1;
        $model->children()->with('branch')->get()
            ->each(function ($child) use (&$htmlCode, $depth, &$counter, $viewPath) {
                $depthCounter = $depth . '.' . $counter;
                $htmlCode .= view($viewPath . '.tree-li', ['child' => $child, 'counter' => $depthCounter])->render();
                if ($child->children) {
                    $htmlCode .= '<ul style="display:none" id="ul-tree-' . $child->id . '">';
                    $this->getMyChildren($child, $htmlCode, $depthCounter, $viewPath);
                    $htmlCode .= '</ul>';
                }
                $counter++;
                $htmlCode .= '</li>';
            });
    }

    function createForm($parentId, string $viewPath)
    {
        $validationClass = 'App\Http\Requests\TreeCreateRequest';
        $formRoute = route('admin:financial_management.type_revenue.store');
        return view($viewPath . '.create-form', [
            'parentId' => $parentId,
            'validationClass' => $validationClass,
            'formRoute' => $formRoute,
        ])->render();
    }
}
