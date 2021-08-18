<?php

Route::resource('/return-sale-receipts', 'ReturnedSaleReceiptsController');

Route::post('return-sale-receipts/select-type', 'ReturnedSaleReceiptsController@selectType')->name('return.sale.receipts.select.type');
Route::post('return-sale-receipts/get-type-item', 'ReturnedSaleReceiptsController@getTypeItems')->name('return.sale.receipts.get.type.items');

//
Route::post('/return-sale-receipts/select-part', 'ReturnedSaleReceiptsController@selectPartRaw')->name('return.sale.receipts.select.part');
Route::get('/return-sale-receipts/print/data', 'ReturnedSaleReceiptsController@print')->name('return.sale.receipts.print');
Route::post('/return-sale-receipts/terms', 'ReturnedSaleReceiptsController@terms')->name('return.sale.receipts.terms');



// purchase quotations execution
//Route::post('/sale-quotations-execution/save', 'SaleQuotationExecutionController@save')->name('sale.quotations.execution.save');


// purchase quotations library
//Route::post('sale-quotations/library/get-files', 'SaleQuotationLibraryController@getFiles')->name('sale.quotations.library.get.files');
//Route::post('sale-quotations/upload_library', 'SaleQuotationLibraryController@uploadLibrary')->name('sale.quotations.upload_library');
//Route::post('sale-quotations/library/file-delete', 'SaleQuotationLibraryController@destroyFile')->name('sale.quotations.library.file.delete');
