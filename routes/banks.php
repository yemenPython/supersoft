<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Banks')
    ->prefix('banks')
    ->name('banks.')->group(function () {
        Route::post('bank_data/assign_products', 'BankDataController@assignProducts')->name('bank_data.assign_products');
        Route::get('bank_data/start_dealing/{id}', 'BankDataController@StartDealing')->name('bank_data.StartDealing');
        Route::post('bank_data/deleteSelected', 'BankDataController@deleteSelected')->name('bank_data.deleteSelected');
        Route::resource('bank_data', 'BankDataController');

        // library
        Route::post('bank_data/library/get-files', 'BankDataLibraryController@getFiles')->name('bank_data.library.get.files');
        Route::post('bank_data/upload_library', 'BankDataLibraryController@uploadLibrary')->name('bank_data.upload_library');
        Route::post('bank_data/library/file-delete', 'BankDataLibraryController@destroyFile')->name('bank_data.library.file.delete');

        //branch products
        Route::post('deleteSelected', 'BranchProductController@deleteSelected')->name('branch_product.deleteSelected');
        Route::resource('branch_product', 'BranchProductController');
    });
