<?php
Route::post('sale-supply-orders-deleteSelected', 'SaleSupplyOrderController@deleteSelected')->name('sale.supply.orders.deleteSelected');

Route::resource('sale-supply-orders', 'SaleSupplyOrderController');

//purchase quotations
Route::post('/sale-supply-orders/add-sale-quotations', 'SaleSupplyOrderController@addSaleQuotations')->name('sale.supply.orders.add.sale.quotations');

Route::post('/sale-supply-orders/select-part', 'SaleSupplyOrderController@selectPartRaw')->name('sale.supply.orders.select.part');
Route::get('/sale-supply-orders/print/data', 'SaleSupplyOrderController@print')->name('sale.supply.orders.print');
Route::post('/sale-supply-orders/terms', 'SaleSupplyOrderController@terms')->name('sale.supply.orders.terms');

// PRICE SEGMENTS
Route::post('sale-supply-orders/price-segments', 'SaleSupplyOrderController@priceSegments')->name('sale.supply.orders.price.segments');

// purchase quotations execution
Route::post('/supply-orders-execution/save', 'SupplyOrderExecutionController@save')->name('supply.orders.execution.save');

// purchase quotations library
//Route::post('supply-orders/library/get-files', 'SupplyOrderLibraryController@getFiles')->name('supply.orders.library.get.files');
//Route::post('supply-orders/upload_library', 'SupplyOrderLibraryController@uploadLibrary')->name('supply.orders.upload_library');
//Route::post('supply-orders/library/file-delete', 'SupplyOrderLibraryController@destroyFile')->name('supply.orders.library.file.delete');
