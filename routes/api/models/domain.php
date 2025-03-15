<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainController@policies')
	->name('policies');

Route::get('policy', 'DomainController@policy')
	->name('policy');

Route::get('index', 'DomainController@index')
	->name('index');

Route::get('show', 'DomainController@show')
	->name('show');

Route::post('create', 'DomainController@create')
	->name('create');

Route::put('update', 'DomainController@update')
	->name('update');

Route::delete('delete', 'DomainController@delete')
	->name('delete');

Route::post('restore', 'DomainController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainController@export')
	->name('export');