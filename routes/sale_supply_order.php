<?php
Route::post('sale-supply-orders-deleteSelected', 'SaleSupplyOrderController@deleteSelected')->name('sale.supply.orders.deleteSelected');

Route::resource('sale-supply-orders', 'SaleSupplyOrderController');

Route::post('sale-supply-orders/{saleSupplyOrder}/update', 'SaleSupplyOrderController@update')->name('sale-supply-orders.update');

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
Route::post('sale-supply/library/get-files', 'SaleSupplyLibraryController@getFiles')->name('sale.supply.library.get.files');
Route::post('sale-supply/upload_library', 'SaleSupplyLibraryController@uploadLibrary')->name('sale.supply.upload_library');
Route::post('sale-supply/library/file-delete', 'SaleSupplyLibraryController@destroyFile')->name('sale.supply.library.file.delete');


Route::post('sale-supply-orders-get-sale-quotations', 'SaleSupplyOrderController@getSaleQuotations')->name('sale.supply.orders.get.sale.quotation');

Route::post('sale-supply-orders-check-stock', 'SaleSupplyOrderController@checkStock')->name('sale.supply.orders.check.stock');
