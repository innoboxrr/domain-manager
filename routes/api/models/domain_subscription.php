<?php

use Illuminate\Support\Facades\Route;

Route::get('policies', 'DomainSubscriptionController@policies')
	->name('policies');

Route::get('policy', 'DomainSubscriptionController@policy')
	->name('policy');

Route::get('index', 'DomainSubscriptionController@index')
	->name('index');

Route::get('show', 'DomainSubscriptionController@show')
	->name('show');

Route::post('create', 'DomainSubscriptionController@create')
	->name('create');

Route::put('update', 'DomainSubscriptionController@update')
	->name('update');

Route::delete('delete', 'DomainSubscriptionController@delete')
	->name('delete');

Route::post('restore', 'DomainSubscriptionController@restore')
	->name('restore');

Route::delete('force-delete', 'DomainSubscriptionController@forceDelete')
	->name('force.delete');

Route::post('export', 'DomainSubscriptionController@export')
	->name('export');