<?php

Route::resource('maintenance-cards', 'MaintenanceCardController');

// delete selected
Route::post('work-cards-deleteSelected', 'MaintenanceCardController@deleteSelected')->name('work.cards.deleteSelected');

Route::post('maintenance-cards-assets', 'MaintenanceCardController@getAssets')->name('maintenance.cards.assets');
