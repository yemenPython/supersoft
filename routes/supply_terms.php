<?php

Route::resource('/supply-terms', 'SupplyTermsController');
Route::post('/supply-terms/deleteSelected', 'SupplyTermsController@deleteSelected')->name('supply.terms.deleteSelected');
