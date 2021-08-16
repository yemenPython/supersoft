<?php

Route::resource('tax_card', 'TaxCardController');
Route::post('tax_card-deleteSelected', 'TaxCardController@deleteSelected')->name('tax_card.deleteSelected');
Route::post('tax_card-groups-by-branch', 'TaxCardController@getSupplierGroupsByBranch')->name('tax_card.groups.by.branch.by.branch');

Route::post('tax_card/upload-upload_library', 'TaxCardController@uploadLibrary')->name('tax_card.upload.upload_library');
Route::post('tax_card/upload_library', 'TaxCardController@getFiles')->name('tax_card.upload_library');
Route::post('tax_card/upload_library/file-delete', 'TaxCardController@destroyFile')->name('tax_card.upload_library.file.delete');

