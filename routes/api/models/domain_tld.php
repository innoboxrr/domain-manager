<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainTldController@policies')
	->name('policies');

Route::get('policy', 'DomainTldController@policy')
	->name('policy');

Route::get('index', 'DomainTldController@index')
	->name('index');

Route::get('show', 'DomainTldController@show')
	->name('show');

Route::post('create', 'DomainTldController@create')
	->name('create');

Route::put('update', 'DomainTldController@update')
	->name('update');

Route::delete('delete', 'DomainTldController@delete')
	->name('delete');

Route::post('restore', 'DomainTldController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainTldController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainTldController@export')
	->name('export');