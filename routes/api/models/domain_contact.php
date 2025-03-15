<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainContactController@policies')
	->name('policies');

Route::get('policy', 'DomainContactController@policy')
	->name('policy');

Route::get('index', 'DomainContactController@index')
	->name('index');

Route::get('show', 'DomainContactController@show')
	->name('show');

Route::post('create', 'DomainContactController@create')
	->name('create');

Route::put('update', 'DomainContactController@update')
	->name('update');

Route::delete('delete', 'DomainContactController@delete')
	->name('delete');

Route::post('restore', 'DomainContactController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainContactController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainContactController@export')
	->name('export');