<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetExpenseRequest;
use App\Http\Requests\Admin\Asset\AssetExpenseRequestUpdate;
use App\Http\Requests\Admin\Asset\AssetReplacementRequest;
use App\Http\Requests\Admin\Asset\UpdateAssetReplacementRequest;
use App\Models\Asset;
use App\Models\AssetExpense;
use App\Models\AssetExpenseItem;
use App\Models\AssetGroup;
use App\Models\AssetReplacement;
use App\Models\AssetReplacementItem;
use App\Models\AssetsItemExpense;
use App\Models\AssetsTypeExpense;
use App\Models\Branch;
use App\Models\PurchaseAsset;
use App\Models\Supplier;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

/**
 * Class AssetReplacementController
 * @package App\Http\Controllers\Admin
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetReplacementController extends Controller
{
    use LoggerError;

    public function index(Request $request)
    {

        if ($request->isDataTable) {
            $assetsReplacements = AssetReplacement::select([
                'asset_replacements.id',
                'asset_replacements.number',
                'asset_replacements.date',
                'asset_replacements.time',
                'asset_replacements.total_after_replacement',
                'asset_replacements.total_before_replacement',
                'asset_replacements.branch_id',
                'asset_replacements.created_at',
                'asset_replacements.updated_at',
            ])
                ->leftjoin( 'asset_replacement_items', 'asset_replacements.id', '=', 'asset_replacement_items.asset_replacement_id' );



            if ($request->has( 'branch_id' ) && !empty( $request['branch_id'] ))
                $assetsReplacements->where( 'asset_replacements.branch_id', $request['branch_id'] );

            if ($request->has( 'number' ) && !empty( $request['number'] ))
                $assetsReplacements->where( 'asset_replacements.number', $request['number'] );


            if ($request->has( 'asset_group_id' ) && !empty( $request->asset_group_id ))
                $assetsReplacements->where( 'asset_replacement_items.asset_group_id', $request['asset_group_id'] );

            if ($request->has( 'asset_id' ) && !empty( $request->asset_id ))
                $assetsReplacements->where( 'asset_replacement_items.asset_id', $request['asset_id'] );

            whereBetween($assetsReplacements,'DATE(asset_replacements.date)',$request->date_from,$request->date_to);
            whereBetween( $assetsReplacements, 'asset_replacement_items.value_replacement', $request->value_replacement_from, $request->value_replacement_to );
            return DataTables::of( $assetsReplacements->groupBy( 'asset_replacements.id' ) )
                ->addIndexColumn()
                ->addColumn( 'branch_id', function ($asset) {
                    return '<span class="text-danger">' . optional( $asset->branch )->name . '</span>';
                } )
                ->addColumn( 'number', function ($saleAsset) {
                    return $saleAsset->number;

                } )
                ->addColumn( 'date', function ($saleAsset) {
                    return $saleAsset->date . ' ' . $saleAsset->time;
                } )
                ->addColumn( 'total_before_replacement', function ($assetsReplacement) {
                    return '<span class="label label-warning wg-label">'.number_format($assetsReplacement->total_before_replacement, 2).'</span>';
                } )
                ->addColumn( 'total_after_replacement', function ($assetsReplacement) {
                    return '<span class="label label-warning wg-label">'. number_format($assetsReplacement->total_after_replacement, 2).'</span>';
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
                                            <li> <a class="btn btn-wg-edit hvr-radial-out" href="' . route( "admin:assets_replacements.edit", $assetsReplacement->id ) . '">
    <i class="fa fa-edit"></i>  ' . __( 'Edit' ) . '
        </a></li>
        <li class="btn-style-drop">
        <button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete(' . $assetsReplacement->id . ')">
            <i class="fa fa-trash"></i>  ' . __( 'Delete' ) . '
        </button>

        <form style="display: none" method="POST" id="confirmDelete' . $assetsReplacement->id . '" action="' . route( 'admin:assets_replacements.destroy', $assetsReplacement->id ) . '">
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
                    <form action=' . route( "admin:assets_replacements.deleteSelected" ) . ' method="post" id="deleteSelected">
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
                'branch_id' => 'asset_replacements.branch_id',
                'number' => 'asset_replacements.number',
                'date' => 'date',
                'total_before_replacement' => 'asset_replacements.total_before_replacement',
                'total_after_replacement' => 'asset_replacements.total_after_replacement',
                'created_at' => 'asset_replacements.created_at',
                'updated_at' => 'asset_replacements.updated_at',
                'action' => 'action',
                'options' => 'options'
            ];
            $assets = Asset::all();
            $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
            $numbers = AssetReplacement::pluck('number')->unique();
        return view('admin.assets_replacements.index', compact('js_columns','assets','assetsGroups','numbers'));
        }
    }

    public function create(Request $request): View
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        $branches = Branch::all();
        $lastNumber = AssetReplacement::where( 'branch_id', $branch_id )->orderBy( 'id', 'desc' )->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        return view( 'admin.assets_replacements.create',
            compact( 'assets', 'assetsGroups', 'branches', 'number' ) );
    }

    public function store(AssetReplacementRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $assetReplacement = AssetReplacement::create( $data );
            if ($assetReplacement) {
                foreach ($request->items as $item) {
                    AssetReplacementItem::create( [
                        'value_replacement' => $item['value_replacement'],
                        'value_after_replacement' => $item['value_after_replacement'],
                        'age' => $item['age'],
                        'asset_id' => $item['asset_id'],
                        'asset_replacement_id' => $assetReplacement->id,
                    ] );
                    $asset = Asset::find( $item['asset_id'] );
                    if ($item['purchase_cost'] > 0
                        && $item['value_after_replacement'] > 0
                        && ($item['purchase_cost'] / $item['value_after_replacement']) > 0) {
                        $asset_age = (($item['purchase_cost'] + $item['value_replacement']) / $item['value_after_replacement']) / 100;
                    } else {
                        $asset_age = 0;
                    }
                    $asset->update( [
                        'asset_age' => $asset_age,
                        'annual_consumtion_rate' => $item['value_after_replacement'],
                    ] );
                }
            }
            return redirect()->to( 'admin/assets_replacements' )
                ->with( ['message' => __( 'words.assets-replacement-created' ), 'alert-type' => 'success'] );
        } catch (Exception $exception) {
            $this->logErrors( $exception );
            return back()->with( ['message' => __( 'words.something-went-wrong' ), 'alert-type' => 'error'] );
        }
    }

    public function edit(Request $request, int $id)
    {
        $assetReplacement = AssetReplacement::findOrFail( $id );
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $assetReplacement->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        $branches = Branch::all();
        return view( 'admin.assets_replacements.edit',
            compact( 'assets', 'assetsGroups', 'branches', 'assetReplacement' ) );
    }

    public function show(int $id): JsonResponse
    {
        $assetReplacement = AssetReplacement::findOrFail( $id );
        $view = view( 'admin.assets_replacements.show', compact( 'assetReplacement' ) )->render();
        return response()->json( [
            'view' => $view
        ] );

    }

    public function update(UpdateAssetReplacementRequest $request, int $id): RedirectResponse
    {
        try {
            $assetReplacement = AssetReplacement::findOrFail( $id );
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $assetExpenseUpdated = $assetReplacement->update( $data );
            $assetReplacement->assetReplacementItems()->delete();
            if ($assetExpenseUpdated) {
                foreach ($request->items as $item) {
                    AssetReplacementItem::create( [
                        'value_replacement' => $item['value_replacement'],
                        'value_after_replacement' => $item['value_after_replacement'],
                        'age' => $item['age'],
                        'asset_id' => $item['asset_id'],
                        'asset_replacement_id' => $assetReplacement->id,
                    ] );
                    $asset = Asset::find( $item['asset_id'] );
                    if ($item['purchase_cost'] > 0
                        && $item['value_after_replacement'] > 0
                        && ($item['purchase_cost'] / $item['value_after_replacement']) > 0) {
                        $asset_age = (($item['purchase_cost'] + $item['value_replacement']) / $item['value_after_replacement']) / 100;
                    } else {
                        $asset_age = 0;
                    }
                    $asset->update( [
                        'asset_age' => $asset_age,
                        'annual_consumtion_rate' => $item['value_after_replacement'],
                    ] );
                }
            }
            return redirect()->to( 'admin/assets_replacements' )
                ->with( ['message' => __( 'words.assets-replacement-updated' ), 'alert-type' => 'success'] );
        } catch (Exception $exception) {
            $this->logErrors( $exception );
            return back()->with( ['message' => __( 'words.something-went-wrong' ), 'alert-type' => 'error'] );
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $assetExpense = AssetReplacement::findOrFail( $id );
        $assetExpense->assetReplacementItems()->delete();
        $assetExpense->delete();
        return redirect()->to( 'admin/assets_replacements' )
            ->with( ['message' => __( 'words.assets-replacement-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset( $request->ids )) {
            $assets = AssetReplacementItem::whereIn( 'id', $request->ids )->get();
            foreach ($assets as $asset) {
                $asset->assetReplacementItems()->delete();
                $asset->delete();
            }
            return redirect()->to( 'admin/assets_replacements' )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect()->to( 'admin/assets_replacements' )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function getItemsByAssetId(Request $request): JsonResponse
    {
        if (is_null( $request->asset_id )) {
            return response()->json( __( 'please select valid Asset' ), 400 );
        }
        if (authIsSuperAdmin() && is_null( $request->branch_id )) {
            return response()->json( __( 'please select valid branch' ), 400 );
        }
        $index = $request->index;
        $asset = Asset::with( 'group' )->find( $request->asset_id );
        $assetGroup = $asset->group;
        $view = view( 'admin.assets_replacements.row',
            compact( 'asset', 'assetGroup', 'index' )
        )->render();
        return response()->json( [
            'items' => $view
        ] );
    }
}
