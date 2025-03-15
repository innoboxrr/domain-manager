<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainPaymentController@policies')
	->name('policies');

Route::get('policy', 'DomainPaymentController@policy')
	->name('policy');

Route::get('index', 'DomainPaymentController@index')
	->name('index');

Route::get('show', 'DomainPaymentController@show')
	->name('show');

Route::post('create', 'DomainPaymentController@create')
	->name('create');

Route::put('update', 'DomainPaymentController@update')
	->name('update');

Route::delete('delete', 'DomainPaymentController@delete')
	->name('delete');

Route::post('restore', 'DomainPaymentController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainPaymentController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainPaymentController@export')
	->name('export');