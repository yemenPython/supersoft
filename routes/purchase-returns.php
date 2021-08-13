<?php
//purchase-invoices-returns
Route::get('/purchase-returns', 'PurchaseReturnsController@index')->name('purchase_returns.index');
Route::get('/purchase-returns/create', 'PurchaseReturnsController@create')->name('purchase_returns.create');
Route::get('/purchase-returns/edit/{purchaseReturn}', 'PurchaseReturnsController@edit')->name('purchase_returns.edit');
Route::post('/purchase-returns/store', 'PurchaseReturnsController@store')->name('purchase_returns.store');
Route::put('/purchase-returns/update/{purchaseReturn}', 'PurchaseReturnsController@update')->name('purchase_returns.update');
Route::delete('/purchase-returns/destroy/{purchaseReturn}', 'PurchaseReturnsController@destroy')->name('purchase_returns.destroy');

//purchase receipt
Route::post('/purchase-returns/purchase-receipt', 'PurchaseReturnsController@getPurchaseReceipts')->name('purchase.returns.purchase-receipts');
Route::post('/purchase-returns/add-purchase-receipts', 'PurchaseReturnsController@addPurchaseReceipts')->name('purchase.returns.add.purchase.receipts');

//purchase invoice
Route::post('/purchase-returns/select-purchase-invoice', 'PurchaseReturnsController@SelectPurchaseInvoice')->name('purchase.returns.select.purchase.invoice');

//part quantity
Route::post('/purchase-returns/part-quantity', 'PurchaseReturnsController@showPartQuantity')->name('purchase.returns.show.part.quantity');


Route::get('/purchase-returns/revenues/{id}', 'PurchaseReturnsController@showRevenues')->name('purchase_returns.revenues');
Route::post('/purchase-returns/get-Purchase-invoice-by-id', 'PurchaseReturnsController@getPurchaseInvoice')->name('purchase_returns.getPurchaseInvoice');
Route::get('/purchase-returns/show', 'PurchaseReturnsController@show')->name('purchase_returns.show');
Route::get('/purchase-returns/getInvoiceByBranch', 'PurchaseReturnsController@getInvoiceByBranch')->name('purchase_returns.getInvoiceByBranch');
Route::post('/purchase-returns/delete-selected', 'PurchaseReturnsController@deleteSelected')->name('purchase_returns.deleteSelected');

Route::post('/purchase-return/terms', 'PurchaseReturnsController@terms')->name('purchase.return.terms');

Route::get('/purchase-returns/data/show/{purchaseReturn}', 'PurchaseReturnsController@showData')->name('purchase.returns.data.show');

// purchase quotations library
Route::post('purchase-returns/library/get-files', 'PurchaseReturnLibraryController@getFiles')->name('purchase.returns.library.get.files');
Route::post('purchase-returns/upload_library', 'PurchaseReturnLibraryController@uploadLibrary')->name('purchase.returns.upload_library');
Route::post('purchase-returns/library/file-delete', 'PurchaseReturnLibraryController@destroyFile')->name('purchase.returns.library.file.delete');
