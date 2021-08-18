<?php

use Illuminate\Support\Facades\Route;

Route::namespace('FinancialManagement')
    ->prefix('financial_management')
    ->name('financial_management.')->group(function () {
        Route::resource('type_revenue', 'TypeRevenueController');
    });
