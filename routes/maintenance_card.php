<?php

Route::resource('maintenance-cards', 'MaintenanceCardController');

// delete selected
Route::post('work-cards-deleteSelected', 'MaintenanceCardController@deleteSelected')->name('maintenance.cards.deleteSelected');

Route::post('maintenance-cards-assets', 'MaintenanceCardController@getAssets')->name('maintenance.cards.assets');

Route::post('maintenance_centers/deleteSelected', 'MaintenanceCenterController@deleteSelected')->name('maintenance_centers.deleteSelected');
Route::resource('maintenance_centers', 'MaintenanceCenterController');
