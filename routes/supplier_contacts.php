<?php

Route::post('suppliers/new-contact', 'SupplierContactsController@newContact')->name('suppliers.new.contact');
Route::post('suppliers/update-contact', 'SupplierContactsController@update')->name('suppliers.contact.update');
Route::post('suppliers/destroy-contact', 'SupplierContactsController@destroy')->name('suppliers.contact.destroy');


Route::post('suppliers_contacts/deleteSelected', 'SupplierContactController@deleteSelected')->name('suppliers_contacts.deleteSelected');

Route::get('suppliers_contacts/{supplier}', 'SupplierContactController@index')->name('suppliers_contacts.index');
Route::post('suppliers_contacts/{supplier}', 'SupplierContactController@store')->name('suppliers_contacts.store');
Route::delete('suppliers_contacts/{supplierContact}', 'SupplierContactController@destroy')->name('suppliers_contacts.destroy');
