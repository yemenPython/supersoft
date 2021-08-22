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
//Route::post('/purchase-return/terms', 'PurchaseReturnsController@terms')->name('purchase.return.terms');

// purchase quotations library
//Route::post('purchase-returns/library/get-files', 'PurchaseReturnLibraryController@getFiles')->name('purchase.returns.library.get.files');
//Route::post('purchase-returns/upload_library', 'PurchaseReturnLibraryController@uploadLibrary')->name('purchase.returns.upload_library');
//Route::post('purchase-returns/library/file-delete', 'PurchaseReturnLibraryController@destroyFile')->name('purchase.returns.library.file.delete');
