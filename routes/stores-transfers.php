<?php
//stores transfers
Route::resource('stores-transfers', 'StoreTransferCont')->except('update');
Route::post('stores-transfers/{id}/update', 'StoreTransferCont@update')->name('stores-transfers.update');

Route::post('stores-transfers-deleteSelected', 'StoreTransferCont@deleteSelected')->name('stores-transfers.deleteSelected');
Route::post('stores-transfers-get-store-parts' ,'StoreTransferCont@getStoreParts')->name('get-store-parts');
Route::get('stores-transfers-get-stores/{branch}' ,'StoreTransferCont@get_branch_stores')->name('get-branch-stores');

// new routes
Route::post('stores-transfers-select-part' ,'StoreTransferCont@selectPartRaw')->name('stores.transfers.select.part');
Route::post('stores-transfers-get-price-segments' ,'StoreTransferCont@getPriceSegments')->name('stores.transfers.get.price.segments');
Route::get('stores_transfers/print/{id}' ,'StoreTransferCont@getViewToPrint')->name('stores.transfers.print');


// purchase quotations library
Route::post('stores-transfers/library/get-files', 'StoreTransferLibraryController@getFiles')->name('stores.transfers.library.get.files');
Route::post('stores-transfers/upload_library', 'StoreTransferLibraryController@uploadLibrary')->name('stores.transfers.upload_library');
Route::post('stores-transfers/library/file-delete', 'StoreTransferLibraryController@destroyFile')->name('stores.transfers.library.file.delete');

//check stock
Route::post('stores-transfers-check-stock', 'StoreTransferCont@checkStock')->name('stores.transfers.check.stock');
