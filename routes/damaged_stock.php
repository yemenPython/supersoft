<?php

//DAMAGED STOCK
Route::resource('damaged-stock', 'DamagedStockController')->except('update');
Route::post('damaged-stock/{damagedStock}/update', 'DamagedStockController@update')->name('damaged-stock.update');

//ajax
Route::post('damaged-stock/select-part', 'DamagedStockController@selectPartRaw')->name('damage.stock.select.part');
Route::post('damaged-stock/price-segments', 'DamagedStockController@priceSegments')->name('damage.stock.price.segments');


// Ajax employees
Route::post('damaged-stock/employees-percent', 'DamagedStockController@newEmployeesPercent')->name('damage.stock.employees.percent');
Route::post('damaged-stock/delete-employees', 'DamagedStockController@deleteEmployee')->name('damage.stock.delete.employee');
Route::post('damaged-stock/deleteSelected', 'DamagedStockController@deleteSelected')->name('damage.stock.deleteSelected');

Route::get('/damaged-stock/print/data', 'DamagedStockController@print')->name('damage.stock.print');

// library
Route::post('damaged-stock/library/get-files', 'DamagedStockLibraryController@getFiles')->name('damaged.stock.library.get.files');
Route::post('damaged-stock/upload_library', 'DamagedStockLibraryController@uploadLibrary')->name('damaged.stock.upload_library');
Route::post('damaged-stock/library/file-delete', 'DamagedStockLibraryController@destroyFile')->name('damaged.stock.library.file.delete');


//check stock
Route::post('damaged-stock-check-stock', 'DamagedStockController@checkStock')->name('damaged.stock.check.stock');
