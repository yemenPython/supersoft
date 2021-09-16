<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Http\Controllers\Controller;
use App\Models\Banks\BankAccount;
use App\Models\Banks\BankAccountLibrary;
use App\Models\Banks\BankData;
use App\Models\Banks\BankDataLibrary;
use App\Services\LibraryServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BankAccountLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'item_id' => 'required|integer|exists:banks_accounts,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ], [], [
            'title_ar' => __('Title_ar'),
            'files' => __('files'),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        try {
            $item = BankAccount::findOrFail($request['item_id']);
            $library_path = $this->libraryPath($item, 'bank_accounts');
            $director = 'bank_account_library/' . $library_path;
            $files = $request['files'];
            foreach ($files as $index => $file) {
                $fileData = $this->uploadFiles($file, $director);
                $data = [
                    'file_name' => $fileData['file_name'],
                    'extension' => Str::lower($fileData['extension']),
                    'name' => $fileData['name'],
                    'bank_account_id' => $item->id,
                    'title_ar' => $request['title_ar'],
                    'title_en' => $request['title_en'] ?? $request['title_ar'],
                ];
                $files[$index] = BankAccountLibrary::create($data);
            }
            $mainPath = 'storage/bank_account_library/' . $library_path . '/';
            $view = view('admin.partial.upload_library.files', compact('files', 'mainPath'))->render();
        } catch (Exception $e) {
            dd($e->getMessage());
            return response()->json(__('words.back-customer'), 400);
        }
        return response()->json(['view' => $view, 'message' => __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:banks_accounts,id',
        ]);
        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {
            $item = BankAccount::find($request['id']);
            if (!$item) {
                return response('bank not valid', 400);
            }
            $library_path = $item->library_path;
            $files = $item->files;
            $mainPath = 'storage/bank_account_library/' . $library_path . '/';
            $view = view('admin.partial.upload_library.files', compact('files', 'mainPath'))->render();
        } catch (Exception $e) {
            dd($item, $e->getMessage());
            return response('sorry, please try later', 400);
        }
        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:bank_account_libraries,id',
        ]);
        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }
        try {
            $file = BankAccountLibrary::find($request['id']);
            $item = $file->BankAccount;
            $filePath = storage_path('app/public/bank_account_library/' . $item->library_path . '/' . $file->file_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $file->delete();
        } catch (Exception $e) {
            return response('sorry, please try later', 400);
        }
        return response(['id' => $request['id']], 200);
    }
}
