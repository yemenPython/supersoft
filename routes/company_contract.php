<?php

Route::resource('company_contract', 'CompanyContractController');
Route::post('company_contract-deleteSelected', 'CompanyContractController@deleteSelected')->name('company_contract.deleteSelected');

Route::post('company_contract/upload-upload_library', 'CompanyContractController@uploadLibrary')->name('company_contract.upload.upload_library');
Route::post('company_contract/upload_library', 'CompanyContractController@getFiles')->name('company_contract.upload_library');
Route::post('company_contract/upload_library/file-delete', 'CompanyContractController@destroyFile')->name('company_contract.upload_library.file.delete');
