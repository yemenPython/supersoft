<?php
Route::post('purchase-receipts-deleteSelected', 'PurchaseReceiptsController@deleteSelected')->name('purchase-receipts.deleteSelected');

Route::resource('/purchase-receipts', 'PurchaseReceiptsController');
Route::post('/purchase-receipts/select-supply-order', 'PurchaseReceiptsController@selectSupplyOrder')->name('purchase.receipts.select.supply.order');
Route::get('/purchase-receipts/data/print', 'PurchaseReceiptsController@show')->name('purchase.receipts.print');
Route::get('/purchase-receipts/data/show/{purchaseReceipt}', 'PurchaseReceiptsController@showData')->name('purchase.receipts.data.show');


// purchase requests execution
Route::post('/purchase-receipts-execution/save', 'PurchaseReceiptExecutionController@save')->name('purchase.receipts.execution.save');

// purchase request library
Route::post('purchase-receipts/library/get-files', 'PurchaseReceiptLibraryController@getFiles')->name('purchase.receipts.library.get.files');
Route::post('purchase-receipts/upload_library', 'PurchaseReceiptLibraryController@uploadLibrary')->name('purchase.receipts.upload_library');
Route::post('purchase-receipts/library/file-delete', 'PurchaseReceiptLibraryController@destroyFile')->name('purchase.receipts.library.file.delete');
