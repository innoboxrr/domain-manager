<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainDnsController@policies')
	->name('policies');

Route::get('policy', 'DomainDnsController@policy')
	->name('policy');

Route::get('index', 'DomainDnsController@index')
	->name('index');

Route::get('show', 'DomainDnsController@show')
	->name('show');

Route::post('create', 'DomainDnsController@create')
	->name('create');

Route::put('update', 'DomainDnsController@update')
	->name('update');

Route::delete('delete', 'DomainDnsController@delete')
	->name('delete');

Route::post('restore', 'DomainDnsController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainDnsController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainDnsController@export')
	->name('export');