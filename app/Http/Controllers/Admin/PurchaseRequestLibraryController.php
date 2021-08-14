<?php

namespace App\Http\Controllers\Admin;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestLibrary;
use App\Services\LibraryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PurchaseRequestLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'item_id' => 'required|integer|exists:purchase_requests,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseRequest = PurchaseRequest::find($request['item_id']);

            $library_path = $this->libraryPath($purchaseRequest, 'purchase_request');

            $director = 'purchase_request_library/' . $library_path;

            $files = $request['files'];

            foreach ($files as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);

                $data = [
                    'file_name' => $fileData['file_name'],
                    'extension' => Str::lower($fileData['extension']),
                    'name' => $fileData['name'],
                    'purchase_request_id' => $purchaseRequest->id,
                    'title_ar'=> $request['title_ar'],
                    'title_en'=> $request['title_en'] ?? $request['title_ar'],
                ];

                $files[$index] = PurchaseRequestLibrary::create($data);
            }

            $mainPath = 'storage/purchase_request_library/' . $library_path. '/';

            $view = view('admin.partial.upload_library.files', compact('files', 'mainPath'))->render();

        } catch (\Exception $e) {

            return response()->json(__('words.back-customer'), 400);
        }

        return response()->json(['view' => $view, 'message' => __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'id' => 'required|integer|exists:purchase_requests,id',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->first(), 400);
        }

        try {

            $purchaseRequest = PurchaseRequest::find($request['id']);

            $library_path = $purchaseRequest->library_path;

            $files = $purchaseRequest->files;

            $mainPath = 'storage/purchase_request_library/' . $library_path. '/';

            $view = view('admin.partial.upload_library.files', compact('files', 'mainPath'))->render();

        } catch (\Exception $e) {

            return response('sorry, please try later', 400);
        }

        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'id' => 'required|integer|exists:purchase_request_libraries,id',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->first(), 400);
        }

        try {

            $file = PurchaseRequestLibrary::find($request['id']);

            $purchaseRequest = $file->purchaseRequest;

            $filePath = storage_path('app/public/purchase_request_library/' . $purchaseRequest->library_path . '/' . $file->file_name);

            if (File::exists($filePath)) {

                File::delete($filePath);
            }

            $file->delete();

        } catch (\Exception $e) {

            return response('sorry, please try later', 400);
        }

        return response(['id' => $request['id']], 200);
    }
}
