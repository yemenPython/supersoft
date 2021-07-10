<?php

Route::get('/purchase-quotations-compare', 'PurchaseQuotationCompareController@index')->name('purchase.quotations.compare.index');
Route::post('/purchase-quotations-compare/store', 'PurchaseQuotationCompareController@store')->name('purchase.quotations.compare.store');
Route::post('/purchase-quotations-compare/get-parts', 'PurchaseQuotationCompareController@partsByType')->name('purchase.quotations.compare.get.parts');
Route::post('/purchase-quotations-compare/get-quotations', 'PurchaseQuotationCompareController@getPurchaseQuotations')->name('purchase.quotations.compare.get.quotations');
