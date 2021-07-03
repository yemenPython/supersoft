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
use App\Traits\LoggerError;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class AssetReplacementController
 * @package App\Http\Controllers\Admin
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetReplacementController extends Controller
{
    use LoggerError;

    public function index(): View
    {
        $assetsReplacements = AssetReplacement::query();
        return view('admin.assets_replacements.index', [
            'assetsReplacements' => $assetsReplacements->orderBy('id', 'desc')->get()
        ]);
    }

    public function create(Request $request): View
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;
        $assetsGroups = AssetGroup::where('branch_id', $branch_id)->get();
        $assets = Asset::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        $lastNumber = AssetReplacement::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        return view('admin.assets_replacements.create',
            compact('assets', 'assetsGroups', 'branches', 'number'));
    }

    public function store(AssetReplacementRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $assetReplacement = AssetReplacement::create($data);
            if ($assetReplacement) {
                foreach ($request->items as $item) {
                    AssetReplacementItem::create([
                        'value_replacement' => $item['value_replacement'],
                        'value_after_replacement' => $item['value_after_replacement'],
                        'age' => $item['age'],
                        'asset_id' => $item['asset_id'],
                        'asset_replacement_id' => $assetReplacement->id,
                    ]);
                }
            }
            return redirect()->to('admin/assets_replacements')
                ->with(['message' => __('words.assets-replacement-created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(Request $request, int $id)
    {
        $assetReplacement = AssetReplacement::findOrFail($id);
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : $assetReplacement->branch_id;
        $assetsGroups = AssetGroup::where('branch_id', $branch_id)->get();
        $assets = Asset::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        return view('admin.assets_replacements.edit',
            compact('assets', 'assetsGroups', 'branches', 'assetReplacement'));
    }

    public function show(int $id): JsonResponse
    {
        $assetReplacement = AssetReplacement::findOrFail($id);
        $view = view('admin.assets_replacements.show', compact('assetReplacement'))->render();
        return response()->json([
            'view' => $view
        ]);

    }

    public function update(UpdateAssetReplacementRequest $request, int $id): RedirectResponse
    {
        try {
            $assetReplacement = AssetReplacement::findOrFail($id);
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $assetExpenseUpdated = $assetReplacement->update($data);
            $assetReplacement->assetReplacementItems()->delete();
            if ($assetExpenseUpdated) {
                foreach ($request->items as $item) {
                    AssetReplacementItem::create([
                        'value_replacement' => $item['value_replacement'],
                        'value_after_replacement' => $item['value_after_replacement'],
                        'age' => $item['age'],
                        'asset_id' => $item['asset_id'],
                        'asset_replacement_id' => $assetReplacement->id,
                    ]);
                }
            }
            return redirect()->to('admin/assets_replacements')
                ->with(['message' => __('words.assets-replacement-updated'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $assetExpense = AssetReplacement::findOrFail($id);
        $assetExpense->assetReplacementItems()->delete();
        $assetExpense->delete();
        return redirect()->to('admin/assets_replacements')
            ->with(['message' => __('words.assets-replacement-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            $assets = AssetReplacementItem::whereIn('id', $request->ids)->get();
            foreach ($assets as $asset) {
                $asset->assetReplacementItems()->delete();
                $asset->delete();
            }
            return redirect()->to('admin/assets_replacements')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets_replacements')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getItemsByAssetId(Request $request): JsonResponse
    {
        if (is_null($request->asset_id)) {
            return response()->json(__('please select valid Asset'), 400);
        }
        if (authIsSuperAdmin() && is_null($request->branch_id)) {
            return response()->json(__('please select valid branch'), 400);
        }
        $index = rand(1,900000);
        $asset = Asset::with('group')->find($request->asset_id);
        $assetGroup = $asset->group;
        $view = view('admin.assets_replacements.row',
            compact('asset', 'assetGroup', 'index')
        )->render();
        return response()->json([
            'items' => $view
        ]);
    }
}
