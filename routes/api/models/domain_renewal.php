<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainRenewalController@policies')
	->name('policies');

Route::get('policy', 'DomainRenewalController@policy')
	->name('policy');

Route::get('index', 'DomainRenewalController@index')
	->name('index');

Route::get('show', 'DomainRenewalController@show')
	->name('show');

Route::post('create', 'DomainRenewalController@create')
	->name('create');

Route::put('update', 'DomainRenewalController@update')
	->name('update');

Route::delete('delete', 'DomainRenewalController@delete')
	->name('delete');

Route::post('restore', 'DomainRenewalController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainRenewalController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainRenewalController@export')
	->name('export');