<?php

Route::get('sales-invoices-return','SalesInvoiceReturnController@index')->name('sales.invoices.return.index');
Route::get('sales-invoices-return/create','SalesInvoiceReturnController@create')->name('sales.invoices.return.create');

//get-type-items
Route::post('sales-invoices-return-type-items','SalesInvoiceReturnController@getTypeItems')->name('sales.invoices.return.type.items');

Route::post('sales-invoices-return-select-invoice-or-Receipt','SalesInvoiceReturnController@selectSalesInvoiceOrReturnedReceipt')
    ->name('sales.invoices.return.select.invoice.or.Receipt');

//store invoice
Route::post('sales-invoices-return/store','SalesInvoiceReturnController@store')->name('sales.invoices.return.store');

//expenses-receipts
Route::get('sales-invoices-return/expenses-receipts/{invoice}','SalesInvoiceReturnController@expensesReceipts')
    ->name('sales.invoices.return.expense.receipts');

// show invoice
Route::get('sales-invoices-return/show/{salesInvoiceReturn}','SalesInvoiceReturnController@show')->name('sales.invoices.return.show');
Route::get('sales-invoices-return/print/data','SalesInvoiceReturnController@print')->name('sales.invoices.return.print');

//edit invoice
Route::get('sales-invoices-return/edit/{salesInvoiceReturn}','SalesInvoiceReturnController@edit')->name('sales.invoices.return.edit');
Route::post('sales-invoices-return/update/{salesInvoiceReturn}','SalesInvoiceReturnController@update')->name('sales.invoices.return.update');

// deleted invoice
Route::delete('sales-invoices-return/delete/{invoice}','SalesInvoiceReturnController@destroy')->name('sales.invoices.return.destroy');

Route::post('sales-invoices-return-deleteSelected', 'SalesInvoiceReturnController@deleteSelected')
    ->name('sales.invoices.return.deleteSelected');


//part quantity
Route::post('/sales-returns/part-quantity', 'SalesInvoiceReturnController@showPartQuantity')->name('purchase.returns.show.part.quantity');

// terms
Route::post('/sales-returns/terms', 'SalesInvoiceReturnController@terms')->name('sales.return.terms');

// purchase quotations library
Route::post('sales-returns/library/get-files', 'SalesInvoiceReturnLibraryController@getFiles')->name('sates.returns.library.get.files');
Route::post('sales-returns/upload_library', 'SalesInvoiceReturnLibraryController@uploadLibrary')->name('sales.returns.upload_library');
Route::post('sales-returns/library/file-delete', 'SalesInvoiceReturnLibraryController@destroyFile')->name('sales.returns.library.file.delete');
