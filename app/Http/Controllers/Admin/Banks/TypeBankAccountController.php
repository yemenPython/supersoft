<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Http\Controllers\Controller;
use App\Http\Requests\TreeCreateRequest;
use App\Models\Banks\TypeBankAccount;
use App\Services\TreeService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TypeBankAccountController extends Controller
{
    /**
     * @var string
     */
    protected $viewPath = 'admin.banks.type_bank_accounts.';

    /**
     * @var TreeService
     */
    protected $treeService;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $route;

    /**
     * TypeRevenueController constructor.
     * @param TreeService $treeService
     */
    public function __construct(TreeService $treeService)
    {
        $this->treeService = $treeService;
        $this->model = new TypeBankAccount();
        $this->route = route('admin:banks.type_bank_accounts.store');
    }

    public function index(): View
    {
        $tree = $this->treeService->getTree($this->model, $this->viewPath);
        return view($this->viewPath . 'index', compact('tree'));
    }

    public function create(Request $request)
    {
        $parent_id = $request->filled('parent_id') ? $request->parent_id : NULL;
        $id = $request->filled('id') ? $request->id : NULL;
        if ($request->action_for != 'create' && !$id) {
            return response(['message' => __('words.choose-part-type-to-edit')] ,400);
        }
        try {
            if ($request->action_for == 'create') {
                $form_code = $this->treeService->createForm($parent_id, $this->viewPath, $this->route);
            } else {
                $model = TypeBankAccount::findOrFail($id);
                $formRoute = route('admin:banks.type_bank_accounts.update', $model->id);
                $form_code = $this->treeService->editForm($model, $this->viewPath, $formRoute);
            }
            return response(['html_code' => $form_code]);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()] ,400);
        }
    }

    function store(TreeCreateRequest $request) {
        try {
            $this->treeService->insertToDB($this->model, $request->all());
            return back()->with( ['message' => __( 'item created successfully' ), 'alert-type' => 'success']);
        } catch (Exception $e) {
            return back()->with( ['message' => __( 'something went wrong' ), 'alert-type' => 'error']);
        }
    }

    function update(TreeCreateRequest $request , TypeBankAccount $typeBankAccount) {
        try {
            $this->treeService->editInDB($typeBankAccount, $request->all());
            return back()->with( ['message' => __( 'item updated successfully' ), 'alert-type' => 'success']);
        } catch (Exception $e) {
            return back()->with( ['message' => __( 'something went wrong' ), 'alert-type' => 'error']);
        }
    }

    function delete(int $id) {
        $model = TypeBankAccount::findOrFail($id);
        try {
            $this->treeService->deleteFromDB($model);
            return back()->with( ['message' => __( 'item deleted successfully' ), 'alert-type' => 'success']);
        } catch (Exception $e) {
            return back()->with( ['message' => $e->getMessage(), 'alert-type' => 'error']);
        }
    }
}
