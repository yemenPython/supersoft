<?php

//SETTLEMENTS
Route::resource('settlements', 'SettlementController')->except('update');
Route::post('settlements/{settlement}/update', 'SettlementController@update')->name('settlements.update');

//ajax
Route::post('settlements/select-part', 'SettlementController@selectPartRaw')->name('settlements.select.part');
Route::post('settlements/price-segments', 'SettlementController@priceSegments')->name('settlements.price.segments');
Route::post('settlements/deleteSelected', 'SettlementController@deleteSelected')->name('settlements.deleteSelected');

Route::get('/settlements/print/data', 'SettlementController@print')->name('settlements.print');


// library
Route::post('settlements/library/get-files', 'SettlementLibraryController@getFiles')->name('settlements.library.get.files');
Route::post('settlements/upload_library', 'SettlementLibraryController@uploadLibrary')->name('settlements.upload_library');
Route::post('settlements/library/file-delete', 'SettlementLibraryController@destroyFile')->name('settlements.library.file.delete');

// employees
Route::post('settlements/new/employee', 'SettlementController@newEmployee')->name('settlements.new.employee');
Route::post('settlements/destroy/employee', 'SettlementController@destroyEmployee')->name('settlements.destroy.employee');

//check stock
Route::post('settlements-check-stock', 'SettlementController@checkStock')->name('settlements.check.stock');
