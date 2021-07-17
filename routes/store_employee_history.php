<?php

Route::delete('store_employee_history', 'StoresController@destroyEmployeeHistory')->name('store_employee_history.destroyEmployeeHistory');
Route::get('store/store_employee_history/{store}', 'StoreEmployeeHistoryController@index')->name('store_employee_history.index');
Route::post('/store/store_employee_history/store', 'StoreEmployeeHistoryController@store')->name('store_employee_history.store');
Route::delete('/store/store_employee_history/delete/{employeeHistory}', 'StoreEmployeeHistoryController@destroy')->name('store_employee_history.destroy');
Route::post('/store/store_employee_history/delete-selected', 'StoreEmployeeHistoryController@deleteSelected')->name('store_employee_history.deleteSelected');
