<?php

Route::resource('register_added_value', 'RegisterAddedValueController');
Route::post('register_added_value-deleteSelected', 'RegisterAddedValueController@deleteSelected')->name('register_added_value.deleteSelected');

Route::post('register_added_value/upload-upload_library', 'RegisterAddedValueController@uploadLibrary')->name('register_added_value.upload.upload_library');
Route::post('register_added_value/upload_library', 'RegisterAddedValueController@getFiles')->name('register_added_value.upload_library');
Route::post('register_added_value/upload_library/file-delete', 'RegisterAddedValueController@destroyFile')->name('register_added_value.upload_library.file.delete');
