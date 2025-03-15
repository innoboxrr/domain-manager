<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainProviderController@policies')
	->name('policies');

Route::get('policy', 'DomainProviderController@policy')
	->name('policy');

Route::get('index', 'DomainProviderController@index')
	->name('index');

Route::get('show', 'DomainProviderController@show')
	->name('show');

Route::post('create', 'DomainProviderController@create')
	->name('create');

Route::put('update', 'DomainProviderController@update')
	->name('update');

Route::delete('delete', 'DomainProviderController@delete')
	->name('delete');

Route::post('restore', 'DomainProviderController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainProviderController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainProviderController@export')
	->name('export');