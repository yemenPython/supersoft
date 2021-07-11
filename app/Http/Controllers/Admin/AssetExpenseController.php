<?php

namespace App\Http\Controllers\Admin;

use App\Filters\AssetExpenseFilter;
use App\Models\AssetReplacement;
use Exception;
use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use App\Models\AssetGroup;
use App\Traits\LoggerError;
use Illuminate\Http\Request;
use App\Models\AssetExpense;
use App\Models\AssetExpenseItem;
use App\Models\AssetsTypeExpense;
use Illuminate\Http\JsonResponse;
use App\Models\AssetsItemExpense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Asset\AssetExpenseRequest;
use App\Http\Requests\Admin\Asset\AssetExpenseRequestUpdate;
use Yajra\DataTables\DataTables;

/**
 * Class AssetExpenseController
 * @package App\Http\Controllers\Admin
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetExpenseController extends Controller
{
    use LoggerError;

    /**
     * @var AssetExpenseFilter
     */
    protected $assetExpenseFilter;

    /**
     * AssetExpenseController constructor.
     * @param AssetExpenseFilter $assetExpenseFilter
     */
    public function __construct(AssetExpenseFilter $assetExpenseFilter)
    {
        $this->assetExpenseFilter = $assetExpenseFilter;
    }

    public function index(Request $request)
    {

        if ($request->isDataTable) {
            $assetExpenses = AssetExpense::select( ['*'] );
            if ($request->hasAny( (new AssetExpense())->getFillable() )
                || $request->has( 'dateFrom' )
                || $request->has( 'dateTo' )
                || $request->has( 'store_id' )
                || $request->has( 'settlement_type' )
                || $request->has( 'barcode' )
                || $request->has( 'supplier_barcode' )
                || $request->has( 'partId' )
            ) {
                $assetExpenses = $this->assetExpenseFilter->filter( $request );
            }
            return DataTables::of( $assetExpenses )
                ->addIndexColumn()
                ->addColumn( 'branch_id', function ($assetExpense) {
                    return '<span class="text-danger">' . optional( $assetExpense->branch )->name . '</span>';
                } )
                ->addColumn( 'number', function ($assetExpense) {
                    return $assetExpense->number;

                } )
                ->addColumn( 'date', function ($assetExpense) {
                    return $assetExpense->date . ' ' . $assetExpense->time;
                } )
                ->addColumn( 'status', function ($assetExpense) {
                    if ($assetExpense->status == 'pending') {
                        return ' <span class="label label-info wg-label">'.__('Pending').'</span>';
                        }
                    elseif($assetExpense->status == 'accept')
                    {
                        return  '<span class="label label-success wg-label" >'.__('Accepted').'</span>';
                    }elseif($assetExpense->status =='cancel'){
                        return '<span class="label label-danger wg-label" >'.__('Rejected').'</span>';
                    }
                } )
                ->addColumn( 'total', function ($assetsReplacement) {
                    return '<span class="label label-warning wg-label">' .number_format($assetsReplacement->total, 2) . '</span>';
                } )
                ->addColumn( 'created_at', function ($assetsReplacement) {
                    return $assetsReplacement->created_at;
                } )
                ->addColumn( 'updated_at', function ($assetsReplacement) {
                    return $assetsReplacement->updated_at;
                } )
                ->addColumn( 'action', function ($assetsReplacement) {
                    return '
                      <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i> ' . __( "Options" ) . '<span class="caret"></span></button>
                                          <ul class="dropdown-menu dropdown-wg">
                                            <li> <a class="btn btn-wg-edit hvr-radial-out" href="' . route( "admin:assets_expenses.edit", $assetsReplacement->id ) . '">
    <i class="fa fa-edit"></i>  ' . __( 'Edit' ) . '
        </a></li>
        <li class="btn-style-drop">
        <button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete(' . $assetsReplacement->id . ')">
            <i class="fa fa-trash"></i>  ' . __( 'Delete' ) . '
        </button>

        <form style="display: none" method="POST" id="confirmDelete' . $assetsReplacement->id . '" action="' . route( 'admin:assets_expenses.destroy', $assetsReplacement->id ) . '">
            <input type="hidden" name="_method" value="DELETE">
           ' . @csrf_field() . '
        </form>
        </li>
        <li>
        <a style="cursor:pointer" class="btn btn-print-wg text-white  "
           data-toggle="modal" onclick="getPrintData(' . $assetsReplacement->id . ')"
           data-target="#boostrapModal" title="' . __( 'print' ) . '">
            <i class="fa fa-print"></i> ' . __( 'Print' ) . '</a>
        </li>
          </ul> </div>
                 ';
                } )->addColumn( 'options', function ($assetsReplacement) {
                    return '
                    <form action=' . route( "admin:assets_expenses.deleteSelected" ) . ' method="post" id="deleteSelected">
                    ' . @csrf_field() . '
        <div class="checkbox danger">
        <input type="checkbox" name="ids[]" value="' . $assetsReplacement->id . '" id="checkbox-' . $assetsReplacement->id . '">
        <label for="checkbox-' . $assetsReplacement->id . '"></label>
          </div>
            </form>
                    ';
                } )
                ->rawColumns( ['action'] )
                ->rawColumns( ['actions'] )
                ->escapeColumns( [] )
                ->make( true );
        } else {

            $js_columns = [
                'DT_RowIndex' => 'DT_RowIndex',
                'branch_id' => 'asset_expenses.branch_id',
                'number' => 'asset_expenses.number',
                'date' => 'date',
                'status' => 'asset_expenses.status',
                'total' => 'asset_expenses.total',
                'created_at' => 'asset_expenses.created_at',
                'updated_at' => 'asset_expenses.updated_at',
                'action' => 'action',
                'options' => 'options'
            ];

            $branches = Branch::all();

            return view( 'admin.assets_expenses.index', compact( 'branches','js_columns' ) );
        }
    }

    public function create(Request $request): View
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        $branches = Branch::all();
        $lastNumber = AssetExpense::where( 'branch_id', $branch_id )->orderBy( 'id', 'desc' )->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        return view( 'admin.assets_expenses.create',
            compact( 'assets', 'assetsGroups', 'branches', 'number' ) );
    }

    public function store(AssetExpenseRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();
            if (!$request->notes) {
                $data['notes'] = ' ';
            }
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $asset = AssetExpense::create( $data );
            if ($asset) {
                foreach ($request->items as $item) {
                    AssetExpenseItem::create( [
                        'price' => $item['price'],
                        'asset_id' => $item['asset_id'],
                        'asset_expense_id' => $asset->id,
                        'asset_expense_item_id' => $item['asset_expense_item_id'],
                    ] );
                }
            }
            return redirect()->to( 'admin/assets_expenses' )
                ->with( ['message' => __( 'words.expense-item-created' ), 'alert-type' => 'success'] );
        } catch (Exception $exception) {
            $this->logErrors( $exception );
            return back()->with( ['message' => __( 'words.something-went-wrong' ), 'alert-type' => 'error'] );
        }
    }

    public function edit(Request $request, int $id)
    {
        $assetExpense = AssetExpense::findOrFail( $id );
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $assetExpense->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        $branches = Branch::all();
        $lastNumber = AssetExpense::orderBy( 'id', 'desc' )->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        $assetExpensesTypes = AssetsTypeExpense::where( 'branch_id', $branch_id )->get();
        $assetExpensesItems = AssetsItemExpense::where( 'branch_id', $branch_id )->get();
        return view( 'admin.assets_expenses.edit',
            compact( 'assets', 'assetsGroups', 'branches', 'number', 'assetExpense', 'assetExpensesItems', 'assetExpensesTypes' ) );
    }

    public function show(int $id): JsonResponse
    {
        $assetExpense = AssetExpense::with( 'assetExpensesItems' )->findOrFail( $id );
        $view = view( 'admin.assets_expenses.show', compact( 'assetExpense' ) )->render();
        return response()->json( [
            'view' => $view
        ] );

    }

    public function update(AssetExpenseRequestUpdate $request, int $id): RedirectResponse
    {
        try {
            $assetExpense = AssetExpense::findOrFail( $id );
            $data = $request->all();
            if (!$request->notes) {
                $data['notes'] = ' ';
            }
            $data['user_id'] = Auth::id();
            $assetExpenseUpdated = $assetExpense->update( $data );
            $assetExpense->assetExpensesItems()->delete();
            if ($assetExpenseUpdated) {
                foreach ($request->items as $item) {
                    AssetExpenseItem::create( [
                        'price' => $item['price'],
                        'asset_id' => $item['asset_id'],
                        'asset_expense_id' => $assetExpense->id,
                        'asset_expense_item_id' => $item['asset_expense_item_id'],
                    ] );
                }
            }
            return redirect()->to( 'admin/assets_expenses' )
                ->with( ['message' => __( 'words.expense-item-updated' ), 'alert-type' => 'success'] );
        } catch (Exception $exception) {
            $this->logErrors( $exception );
            return back()->with( ['message' => __( 'words.something-went-wrong' ), 'alert-type' => 'error'] );
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $assetExpense = AssetExpense::findOrFail( $id );
        $assetExpense->assetExpensesItems()->delete();
        $assetExpense->delete();
        return redirect()->to( 'admin/assets_expenses' )
            ->with( ['message' => __( 'words.expense-type-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset( $request->ids )) {
            $assets = AssetExpense::whereIn( 'id', $request->ids )->get();
            foreach ($assets as $asset) {
                $asset->assetExpensesItems()->delete();
                $asset->delete();
            }
            return redirect()->to( 'admin/assets_expenses' )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect()->to( 'admin/assets_expenses' )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function getAssetsByAssetGroup(Request $request): JsonResponse
    {
        if (empty($request->branch_id) ){
            return response()->json(__('please select valid Branch'), 400);
        }
        $assets = Asset::query();
        if (!$request->asset_group_id) {
            $assets = $assets->where('branch_id' , $request->branch_id);
        }
        if ($request->asset_group_id && $request->branch_id) {
            $assets = $assets->where([
                'asset_group_id' => $request->asset_group_id,
                'branch_id' => $request->branch_id,
            ]);
        }
        $assets = $assets->get();
        $htmlAssets = '<option value="">'.__('Select Assets').'</option>';
        foreach ($assets as $asset) {
            $htmlAssets .= '<option value="' . $asset->id . '">' . $asset->name . '</option>';
        }
        return response()->json( [
            'assets' => $htmlAssets,
        ] );
    }

    public function getItemsByAssetId(Request $request): JsonResponse
    {
        if (is_null( $request->asset_id )) {
            return response()->json( __( 'please select valid Asset' ), 400 );
        }
        if (authIsSuperAdmin() && is_null( $request->branch_id )) {
            return response()->json( __( 'please select valid branch' ), 400 );
        }
        $branchId = $request->branch_id ?? auth()->user()->branch_id;
        $index = $request->index;
        $asset = Asset::with( 'group' )->find( $request->asset_id );
        $assetGroup = $asset->group;
        $assetExpensesTypes = AssetsTypeExpense::where( 'branch_id', $branchId )->get();
        $assetExpensesItems = AssetsItemExpense::where( 'branch_id', $branchId )->get();
        $view = view( 'admin.assets_expenses.row',
            compact( 'asset', 'assetGroup', 'assetExpensesTypes', 'assetExpensesItems', 'index' )
        )->render();
        return response()->json( [
            'items' => $view
        ] );
    }
}
