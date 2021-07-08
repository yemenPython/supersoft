<?php
//purchase-assets

Route::get('sale-assets/getAssetsByAssetId', 'SaleAssetsController@getAssetsByAssetId')->name('consumption_assets.getAssetsByAssetId');
Route::post('/sale-assets/delete-selected', 'SaleAssetsController@deleteSelected')->name('sale-assets.deleteSelected');

Route::get('sale-asset/show','SaleAssetsController@show')->name('sale-assets.show');

Route::resource('sale-assets','SaleAssetsController')->except('show');






