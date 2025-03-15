<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainProviderPaymentController@policies')
	->name('policies');

Route::get('policy', 'DomainProviderPaymentController@policy')
	->name('policy');

Route::get('index', 'DomainProviderPaymentController@index')
	->name('index');

Route::get('show', 'DomainProviderPaymentController@show')
	->name('show');

Route::post('create', 'DomainProviderPaymentController@create')
	->name('create');

Route::put('update', 'DomainProviderPaymentController@update')
	->name('update');

Route::delete('delete', 'DomainProviderPaymentController@delete')
	->name('delete');

Route::post('restore', 'DomainProviderPaymentController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainProviderPaymentController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainProviderPaymentController@export')
	->name('export');