<?php

Route::get('commercial_register/get-select' ,'CommercialRegisterController@getSuppliers')->name('get-commercial_register-select');
Route::resource('commercial_register', 'CommercialRegisterController');
Route::post('commercial_register-deleteSelected', 'CommercialRegisterController@deleteSelected')->name('commercial_register.deleteSelected');
Route::post('commercial_register-groups-by-branch', 'CommercialRegisterController@getSupplierGroupsByBranch')->name('commercial_register.groups.by.branch.by.branch');

Route::post('commercial_register/upload-upload_library', 'CommercialRegisterController@uploadLibrary')->name('commercial_register.upload.upload_library');
Route::post('commercial_register/upload_library', 'CommercialRegisterController@getFiles')->name('commercial_register.upload_library');
Route::post('commercial_register/upload_library/file-delete', 'CommercialRegisterController@destroyFile')->name('commercial_register.upload_library.file.delete');

Route::post('commercial_register/get-sub-groups-by-main-ids', 'CommercialRegisterController@getSubGroupsByMainIds')->name('commercial_register.getSubGroupsByMainIds');
