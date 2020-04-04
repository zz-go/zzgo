<?php

use Illuminate\Support\Facades\Route;

//Route::resource('sys-db-table-definitions', 'SysDbTableDefinitionController');
//Route::resource('sys-db-field-definitions', 'SysDbFieldDefinitionController');

Route::group([
                 'prefix' => 'sys-db-table-definitions',
                 'as'     => 'sys-db-table-definitions.',
             ], function () {


    Route::get('/', ['uses' => 'SysDbTableDefinitionController@index']);
    Route::post('/', ['uses' => 'SysDbTableDefinitionController@store']);


    Route::group([
                     'prefix' => '{sysDbTableDefinition}',
                 ], function () {

        Route::get('/', ['uses' => 'SysDbTableDefinitionController@show']);
        Route::delete('/', ['uses' => 'SysDbTableDefinitionController@destroy']);
        Route::post('/materialize', ['uses' => 'SysDbTableDefinitionController@materialize']);
        Route::post('/link', ['uses' => 'SysDbTableDefinitionController@link']);

        Route::group([
                         'prefix' => 'sys-db-field-definitions',
                         'as'     => 'sys-db-field-definitions.',
                     ], function () {

            Route::get('/', ['uses' => 'SysDbFieldDefinitionController@index']);
            Route::post('/', ['uses' => 'SysDbFieldDefinitionController@store']);

            Route::group([
                             'prefix' => '{sysDbFieldDefinition}',
                         ], function () {

                Route::get('/', ['uses' => 'SysDbFieldDefinitionController@show']);
                Route::delete('/', ['uses' => 'SysDbFieldDefinitionController@destroy']);
                Route::post('/link', ['uses' => 'SysDbFieldDefinitionController@link']);

            });
        });
    });
});

