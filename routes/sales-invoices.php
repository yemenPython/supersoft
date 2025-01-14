<?php

//sales-invoices

Route::get('/sales-invoices', 'SalesInvoicesController@index')->name('sales.invoices.index');
Route::get('/sales-invoices/create', 'SalesInvoicesController@create')->name('sales.invoices.create');
Route::post('/sales-invoices/store', 'SalesInvoicesController@store')->name('sales.invoices.store');
//

//front end routes
Route::post('/sales-invoices-add-customer', 'SalesInvoicesFrontEndController@addCustomer')->name('sales.invoices.add.customer');
Route::post('/sales-invoices-customer-balance', 'SalesInvoicesFrontEndController@customerBalance')->name('sales.invoice.customer.balance');

// data by branch
Route::post('/sales-invoices-data-by-branch', 'SalesInvoicesFrontEndController@dataByBranch')->name('sales.invoices.data.by.branches');

//invoice items
Route::post('/sales-invoices-parts-details', 'SalesInvoicesFrontEndController@partDetails')->name('sales.invoices.parts.details');
Route::post('/sales.invoices.get.purchase.invoice.data','SalesInvoicesFrontEndController@purchaseInvoiceData')
   ->name('sales.invoices.get.purchase.invoice.data');

//// store invoice
////Route::post('/sales-invoices-store', 'SalesInvoicesController@store')->name('sales.invoices.store');
//
//// invoice RevenueReceipts
Route::get('/sales-invoices-revenue-receipts/{invoice}', 'SalesInvoicesController@revenueReceipts')
    ->name('sales.invoices.revenue.receipts');

//// Edit sales-invoice
Route::get('/sales-invoices/edit/{salesInvoice}', 'SalesInvoicesController@edit')->name('sales.invoices.edit');
Route::post('/sales-invoices/update/{salesInvoice}', 'SalesInvoicesController@update')->name('sales.invoices.update');

//// delete Invoice
Route::delete('/sales-invoices/delete/{invoice}', 'SalesInvoicesController@destroy')->name('sales.invoices.destroy');
Route::post('sales-invoices-deleteSelected', 'SalesInvoicesController@deleteSelected')->name('sales.invoices.deleteSelected');
//
//// show Invoice
Route::get('/sales-invoices/show', 'SalesInvoicesController@show')->name('sales.invoices.show');
Route::get('/sales-invoices/data/show/{salesInvoice}', 'SalesInvoicesController@showData')->name('sales.invoices.show.data');
Route::get('/sales-invoices/print', 'SalesInvoicesController@print')->name('sales.invoices.print');
//
//// points discount (get points rules )
Route::post('/customer/points/rules', 'SalesInvoicesFrontEndController@customerPointsRules')->name('customer.points.rules');


////////////////////////////// NEW VERSION ////////////////////////////////////

//Route::resource('sales-invoices', 'SalesInvoicesController');

Route::post('/sales-invoices/select-part', 'SalesInvoicesController@selectPartRaw')->name('sales.invoices.select.part');
Route::get('/sales-invoices/print/data', 'SalesInvoicesController@print')->name('sale.invoices.print');

// PRICE SEGMENTS
Route::post('sales-invoices/price-segments', 'SalesInvoicesController@priceSegments')->name('sales.invoices.price.segments');

Route::post('/sales-invoices/terms', 'SalesInvoicesController@terms')->name('sales.invoices.terms');

Route::post('/sale-invoices/get-sale-quotations', 'SalesInvoicesController@getSaleQuotation')->name('sales.invoices.get.sale.quotations');
Route::post('/sale-invoices/get-sale-supply-order', 'SalesInvoicesController@getSaleSupplyOrder')->name('sales.invoices.get.sale.supply.order');

Route::post('/sale-invoices/add-sale-quotations', 'SalesInvoicesController@addSaleQuotations')->name('sales.invoices.add.sale.quotations');
Route::post('/sale-invoices/add-sale-supply-order', 'SalesInvoicesController@addSaleSupplyOrder')->name('sales.invoices.add.sale.supply.order');

// purchase quotations execution
//Route::post('/purchase-invoices-execution/save', 'PurchaseInvoiceExecutionController@save')->name('purchase.invoices.execution.save');

// purchase quotations library
Route::post('sales-invoices/library/get-files', 'SalesInvoiceLibraryController@getFiles')->name('sales.invoices.library.get.files');
Route::post('sales-invoices/upload_library', 'SalesInvoiceLibraryController@uploadLibrary')->name('sales.invoices.upload_library');
Route::post('sales-invoices/library/file-delete', 'SalesInvoiceLibraryController@destroyFile')->name('sales.invoices.library.file.delete');

//check stock
Route::post('sales-invoices-check-stock', 'SalesInvoicesController@checkStock')->name('sales.invoices.check.stock');

//purchase receipt
//Route::post('/purchase-invoices/purchase-receipt', 'PurchaseInvoicesController@getPurchaseReceipts')->name('purchase.invoices.purchase-receipts');
//Route::post('/purchase-invoices/add-purchase-receipts', 'PurchaseInvoicesController@addPurchaseReceipts')->name('purchase.invoices.add.purchase.receipts');



