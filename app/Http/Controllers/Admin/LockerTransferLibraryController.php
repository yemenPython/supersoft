<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LockerTransfer;
use App\Models\LockerTransferLibrary;
use App\Services\LibraryServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LockerTransferLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'item_id' => 'required|integer|exists:locker_transfers,id',
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
            $item = LockerTransfer::findOrFail($request['item_id']);
            $library_path = $this->libraryPath($item, 'locker_transfers');
            $director = 'locker_transfers/' . $library_path;
            $files = $request['files'];
            foreach ($files as $index => $file) {
                $fileData = $this->uploadFiles($file, $director);
                $data = [
                    'file_name' => $fileData['file_name'],
                    'extension' => Str::lower($fileData['extension']),
                    'name' => $fileData['name'],
                    'locker_transfer_id' => $item->id,
                    'title_ar' => $request['title_ar'],
                    'title_en' => $request['title_en'] ?? $request['title_ar'],
                ];
                $files[$index] = LockerTransferLibrary::create($data);
            }
            $mainPath = 'storage/locker_transfers/' . $library_path . '/';
            $view = view('admin.partial.upload_library.files', compact('files', 'mainPath'))->render();
        } catch (Exception $e) {
            return response()->json(__('words.back-customer'), 400);
        }
        return response()->json(['view' => $view, 'message' => __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:locker_transfers,id',
        ]);
        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {
            $item = LockerTransfer::find($request['id']);
            if (!$item) {
                return response('bank not valid', 400);
            }
            $library_path = $item->library_path;
            $files = $item->files;
            $mainPath = 'storage/locker_transfers/' . $library_path . '/';
            $view = view('admin.partial.upload_library.files', compact('files', 'mainPath'))->render();
        } catch (Exception $e) {
            return response('sorry, please try later', 400);
        }
        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:locker_transfer_libraries,id',
        ]);
        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }
        try {
            $file = LockerTransferLibrary::find($request['id']);
            $item = $file->lockerTransfer;
            $filePath = storage_path('app/public/locker_transfers/' . $item->library_path . '/' . $file->file_name);
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
