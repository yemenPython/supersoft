<?php
//purchase-assets

Route::get('consumption-assets/get_Assets_By_Asset_Id', 'ConsumptionAssetsController@getAssetsByAssetId')->name('consumption_assets.get_Assets_By_Asset_Id');
Route::post('/consumption-assets/delete-selected', 'ConsumptionAssetsController@deleteSelected')->name('consumption-assets.deleteSelected');

Route::get('consumption-asset/show','ConsumptionAssetsController@show')->name('consumption-assets.show');

Route::resource('consumption-assets','ConsumptionAssetsController')->except('show');






