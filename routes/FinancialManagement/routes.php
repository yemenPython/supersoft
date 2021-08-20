<?php

use Illuminate\Support\Facades\Route;

Route::namespace('FinancialManagement')
    ->prefix('financial_management')
    ->name('financial_management.')->group(function () {
        Route::get('type_revenue/delete/{id}', 'TypeRevenueController@delete')->name('type_revenue.delete');
        Route::resource('type_revenue', 'TypeRevenueController');

        Route::get('type_expenses/delete/{id}', 'TypeExpenseController@delete')->name('type_expenses.delete');
        Route::resource('type_expenses', 'TypeExpenseController');

        Route::get('tof_type_expenses/delete/{id}', 'TOFTypeExpenseController@delete')->name('tof_type_expenses.delete');
        Route::resource('tof_type_expenses', 'TOFTypeExpenseController');

        Route::get('tof_type_revenues/delete/{id}', 'TOFTypeRevenueController@delete')->name('tof_type_revenues.delete');
        Route::resource('tof_type_revenues', 'TOFTypeRevenueController');
    });


