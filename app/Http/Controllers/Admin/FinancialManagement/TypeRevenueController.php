<?php

namespace App\Http\Controllers\Admin\FinancialManagement;

use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\TreeService;
use App\Http\Controllers\Controller;
use App\Models\FinancialManagement\TypeRevenue;

class TypeRevenueController extends Controller
{
    /**
     * @var string
     */
    protected $viewPath = 'admin.financial_management.type_revenue.';

    /**
     * @var TreeService
     */
    protected $treeService;

    /**
     * TypeRevenueController constructor.
     * @param TreeService $treeService
     */
    public function __construct(TreeService $treeService)
    {
        $this->treeService = $treeService;
    }

    public function index(): View
    {
        $model = new TypeRevenue();
        $tree = $this->treeService->getTree($model, $this->viewPath);
        return view($this->viewPath . 'index', compact('tree'));
    }

    public function create(Request $request)
    {
        $parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : NULL;
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        if ($request->action_for != 'create' && !$id) {
            return response(['message' => __('words.choose-part-type-to-edit')] ,400);
        }
        try {
            if ($request->action_for == 'create') {
                $form_code = $this->treeService->createForm($parent_id, $this->viewPath);
            } else {
                $form_code = $this->treeService->createForm($parent_id, $this->viewPath);
            }
            return response(['html_code' => $form_code]);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()] ,400);
        }
    }
}
