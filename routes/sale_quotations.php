<?php


Route::resource('/sale-quotations', 'SaleQuotationController');
Route::post('sale-quotations-deleteSelected', 'SaleQuotationController@deleteSelected')->name('sale-quotations.deleteSelected');

Route::post('/sale-quotations/select-part', 'SaleQuotationController@selectPartRaw')->name('sale.quotations.select.part');
Route::get('/sale-quotations/print/data', 'SaleQuotationController@print')->name('sale.quotations.print');
Route::post('/sale-quotations/terms', 'SaleQuotationController@terms')->name('sale.quotations.terms');

// PRICE SEGMENTS
Route::post('sale-quotations/price-segments', 'SaleQuotationController@priceSegments')->name('sale.quotations.price.segments');


// purchase quotations execution
Route::post('/sale-quotations-execution/save', 'SaleQuotationExecutionController@save')->name('sale.quotations.execution.save');

// purchase quotations library
Route::post('sale-quotations/library/get-files', 'PurchaseQuotationLibraryController@getFiles')->name('sale.quotations.library.get.files');
Route::post('sale-quotations/upload_library', 'PurchaseQuotationLibraryController@uploadLibrary')->name('sale.quotations.upload_library');
Route::post('sale-quotations/library/file-delete', 'PurchaseQuotationLibraryController@destroyFile')->name('sale.quotations.library.file.delete');
