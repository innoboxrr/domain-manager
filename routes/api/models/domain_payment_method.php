<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainPaymentMethodController@policies')
	->name('policies');

Route::get('policy', 'DomainPaymentMethodController@policy')
	->name('policy');

Route::get('index', 'DomainPaymentMethodController@index')
	->name('index');

Route::get('show', 'DomainPaymentMethodController@show')
	->name('show');

Route::post('create', 'DomainPaymentMethodController@create')
	->name('create');

Route::put('update', 'DomainPaymentMethodController@update')
	->name('update');

Route::delete('delete', 'DomainPaymentMethodController@delete')
	->name('delete');

Route::post('restore', 'DomainPaymentMethodController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainPaymentMethodController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainPaymentMethodController@export')
	->name('export');