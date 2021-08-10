<?php

Route::post('suppliers/new-bank-account', 'SupplierBankAccountController@newBankAccount')->name('suppliers.new.bank-account');
Route::post('suppliers/update-bank-account', 'SupplierBankAccountController@update')->name('suppliers.bank-account.update');
Route::post('suppliers/destroy-bank-account', 'SupplierBankAccountController@destroy')->name('suppliers.bank-account.destroy');


Route::post('suppliers/bank_accounts/deleteSelected', 'SupplierBankAccountController@deleteSelected')->name('suppliers_bank_account.deleteSelected');

Route::get('suppliers/bank_accounts/{supplier}', 'SupplierBankAccountController@index')->name('suppliers_bank_account.index');
Route::post('suppliers/bank_accounts/{supplier}', 'SupplierBankAccountController@store')->name('suppliers_bank_account.store');
Route::delete('suppliers/bank_accounts/{bankAccount}', 'SupplierBankAccountController@destroy')->name('suppliers_bank_account.destroy');
