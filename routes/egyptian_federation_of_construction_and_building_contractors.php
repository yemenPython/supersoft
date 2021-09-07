<?php

Route::get('egyptian_federation/get-select' ,'EgyptianFederationofConstructionController@getSuppliers')->name('get-egyptian_federation-select');
Route::resource('egyptian_federation', 'EgyptianFederationofConstructionController');
Route::post('egyptian_federation-deleteSelected', 'EgyptianFederationofConstructionController@deleteSelected')->name('egyptian_federation.deleteSelected');
Route::post('egyptian_federation-groups-by-branch', 'EgyptianFederationofConstructionController@getSupplierGroupsByBranch')->name('egyptian_federation.groups.by.branch.by.branch');

Route::post('egyptian_federation/upload-upload_library', 'EgyptianFederationofConstructionController@uploadLibrary')->name('egyptian_federation.upload.upload_library');
Route::post('egyptian_federation/upload_library', 'EgyptianFederationofConstructionController@getFiles')->name('egyptian_federation.upload_library');
Route::post('egyptian_federation/upload_library/file-delete', 'EgyptianFederationofConstructionController@destroyFile')->name('egyptian_federation.upload_library.file.delete');

Route::post('egyptian_federation/get-sub-groups-by-main-ids', 'EgyptianFederationofConstructionController@getSubGroupsByMainIds')->name('egyptian_federation.getSubGroupsByMainIds');
Route::get('egyptian_federation/{egyptian_federation}', 'EgyptianFederationofConstructionController@show')->name('egyptian_federation.show');
