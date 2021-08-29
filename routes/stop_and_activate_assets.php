<?php
//purchase-assets

Route::get('stop_and_activate_assets/get_Assets_By_Asset_Id', 'StopAndActivateAssetsController@getAssetsByAssetId')->name('stop_and_activate_assets.get_Assets_By_Asset_Id');

Route::post('stop_and_activate_assets/get_numbers_by_branch_id', 'StopAndActivateAssetsController@getNumbersByBranchId')->name('stop_and_activate_assets.getNumbersByBranchId');
Route::post('/stop_and_activate_assets/delete-selected', 'StopAndActivateAssetsController@deleteSelected')->name('stop_and_activate_assets.deleteSelected');

Route::get('stop_and_activate_asset/show','StopAndActivateAssetsController@show')->name('stop_and_activate_assets.show');

Route::resource('stop_and_activate_assets','StopAndActivateAssetsController')->except('show');






