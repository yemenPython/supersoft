<?php
//purchase-assets

Route::get('purchase-assets/getAssetsByAssetId', 'PurchaseAssetsController@getAssetsByAssetId')->name('purchase_assets.getAssetsByAssetId');
Route::post('/purchase-assets/delete-selected', 'PurchaseAssetsController@deleteSelected')->name('purchase-assets.deleteSelected');

Route::get('/purchase-assets/show', 'PurchaseAssetsController@show')->name('purchase-assets.show');
Route::post('/InvoiceNumbersBySupplierId', 'PurchaseAssetsController@getInvoiceNumbersBySupplierId')->name('purchase-assets.getInvoiceNumbersBySupplierId');
Route::resource('purchase-assets','PurchaseAssetsController')->except('show');









