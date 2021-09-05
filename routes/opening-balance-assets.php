<?php
//opening-balance-assets

Route::get('opening-balance-assets/getAssetsByAssetId', 'OpeningBalanceAssetsController@getAssetsByAssetId')->name('opening-balance-assets.getAssetsByAssetId');
Route::post('/opening-balance-assets/delete-selected', 'OpeningBalanceAssetsController@deleteSelected')->name('opening-balance-assets.deleteSelected');


Route::post('opening-balance-assets-get_numbers_by_branch_id', 'OpeningBalanceAssetsController@getNumbersByBranchId')->name('opening-balance-assets.get_Numbers_By_BranchId');

Route::get('/opening-balance-assets/show', 'OpeningBalanceAssetsController@show')->name('opening-balance-assets.show');
Route::post('/invoice_numbers_by_supplierId', 'OpeningBalanceAssetsController@getInvoiceNumbersBySupplierId')->name('opening-balance-assets.get_invoice_numbers_by_supplierId');
Route::post('opening-balance-assets/getSuppliersByBranchId', 'OpeningBalanceAssetsController@getSuppliersByBranchId')->name('opening-balance-assets.getSuppliersByBranchId');
Route::resource('opening-balance-assets','OpeningBalanceAssetsController')->except('show');









