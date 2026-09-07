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

Route::post('registrar-sync-operation', 'DomainController@registrarSyncOperation')
	->name('registrar.sync.operation');

Route::post('registrar-upsert-records', 'DomainController@registrarUpsertRecords')
        ->name('registrar.upsert.records');

Route::post('registrar-purchase', 'DomainController@registrarPurchase')
    ->name('registrar.purchase');

Route::post('registrar-renew', 'DomainController@registrarRenew')
    ->name('registrar.renew');

Route::post('registrar-transfer', 'DomainController@registrarTransfer')
    ->name('registrar.transfer');

Route::post('registrar-release', 'DomainController@registrarRelease')
    ->name('registrar.release');

Route::get('registrar-dns', 'DomainController@registrarSyncDns')
    ->name('registrar.dns');
