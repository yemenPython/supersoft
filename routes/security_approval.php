<?php

Route::resource('security_approval', 'SecurityApprovalController');
Route::post('security_approval-deleteSelected', 'SecurityApprovalController@deleteSelected')->name('security_approval.deleteSelected');

Route::post('security_approval/upload-upload_library', 'SecurityApprovalController@uploadLibrary')->name('security_approval.upload.upload_library');
Route::post('security_approval/upload_library', 'SecurityApprovalController@getFiles')->name('security_approval.upload_library');
Route::post('security_approval/upload_library/file-delete', 'SecurityApprovalController@destroyFile')->name('security_approval.upload_library.file.delete');
