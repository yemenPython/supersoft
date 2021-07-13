<?php
//purchase-assets

Route::get('purchase-assets/getAssetsByAssetId', 'PurchaseAssetsController@getAssetsByAssetId')->name('purchase_assets.getAssetsByAssetId');
Route::post('/purchase-assets/delete-selected', 'PurchaseAssetsController@deleteSelected')->name('purchase-assets.deleteSelected');


Route::post('purchase-assets-get_numbers_by_branch_id', 'PurchaseAssetsController@getNumbersByBranchId')->name('purchase-assets.get_Numbers_By_BranchId');

Route::get('/purchase-assets/show', 'PurchaseAssetsController@show')->name('purchase-assets.show');
Route::post('/InvoiceNumbersBySupplierId', 'PurchaseAssetsController@getInvoiceNumbersBySupplierId')->name('purchase-assets.getInvoiceNumbersBySupplierId');
Route::post('purchase-assets/getSuppliersByBranchId', 'PurchaseAssetsController@getSuppliersByBranchId')->name('purchase-assets.getSuppliersByBranchId');
Route::resource('purchase-assets','PurchaseAssetsController')->except('show');









