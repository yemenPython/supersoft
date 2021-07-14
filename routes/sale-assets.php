<?php
//sale-assets

Route::post('sale-assets/get_numbers_by_branch_id', 'SaleAssetsController@getNumbersByBranchId')->name('sale-assets.get_Numbers_By_Branch_Id');

Route::get('sale-assets/getAssetsByAssetId', 'SaleAssetsController@getAssetsByAssetId')->name('consumption_assets.getAssetsByAssetId');
Route::post('/sale-assets/delete-selected', 'SaleAssetsController@deleteSelected')->name('sale-assets.deleteSelected');

Route::get('sale-asset/show','SaleAssetsController@show')->name('sale-assets.show');

Route::resource('sale-assets','SaleAssetsController')->except('show');






