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

        //bank officials
        Route::get('bank_officials/{bankData}', 'BankOfficialController@index')->name('bank_officials.index');
        Route::post('bank_officials/bankData', 'BankOfficialController@store')->name('bank_officials.store');
        Route::delete('bank_officials/delete/{bankOfficial}', 'BankOfficialController@destroy')->name('bank_officials.destroy');
        Route::post('bank_officials/delete-selected', 'BankOfficialController@deleteSelected')->name('bank_officials.deleteSelected');

        //bank commissioners
        Route::get('bank_commissioners/{bankData}', 'BankCommissionerController@index')->name('bank_commissioners.index');
        Route::post('bank_commissioners/bankData', 'BankCommissionerController@store')->name('bank_commissioners.store');
        Route::delete('bank_commissioners/delete/{bankCommissioner}', 'BankCommissionerController@destroy')->name('bank_commissioners.destroy');
        Route::post('bank_commissioners/delete-selected', 'BankCommissionerController@deleteSelected')->name('bank_commissioners.deleteSelected');


        Route::get('type_bank_accounts/delete/{id}', 'TypeBankAccountController@delete')->name('type_bank_accounts.delete');
        Route::resource('type_bank_accounts', 'TypeBankAccountController');
    });
