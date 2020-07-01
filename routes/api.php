<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| zzgo/*
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| GET zzgo/<entity_name>/schema.json
|--------------------------------------------------------------------------
*/

Route::get('sys-db-table-definitions/schema.json', ['uses' => 'SysDbTableDefinitionController@schema'])->name('sys-db-table-definitions.schema');
Route::get('sys-db-field-definitions/schema.json', ['uses' => 'SysDbFieldDefinitionController@schema'])->name('sys-db-field-definitions.schema');

/*
|--------------------------------------------------------------------------
| zzgo/sys-db-table-definitions/*
|--------------------------------------------------------------------------
*/

Route::group([
                 'prefix' => 'sys-db-table-definitions',
                 'as'     => 'sys-db-table-definitions.',
             ], function () {

    /*
    |--------------------------------------------------------------------------
    | GET zzgo/sys-db-table-definitions/
    |--------------------------------------------------------------------------
    */
    Route::get('/', ['uses' => 'SysDbTableDefinitionController@index'])->name('index');

    /*
    |--------------------------------------------------------------------------
    | POST zzgo/sys-db-table-definitions/
    |--------------------------------------------------------------------------
    */
    Route::post('/', ['uses' => 'SysDbTableDefinitionController@store'])->name('store');

    /*
    |--------------------------------------------------------------------------
    | zzgo/sys-db-table-definitions/<sysDbTableDefinition>/*
    |--------------------------------------------------------------------------
    */

    Route::group([
                     'prefix' => '{sysDbTableDefinition}',
                 ], function () {

        /*
        |--------------------------------------------------------------------------
        | GET zzgo/sys-db-table-definitions/<sysDbTableDefinition>
        |--------------------------------------------------------------------------
        */

        Route::get('/', ['uses' => 'SysDbTableDefinitionController@show'])->name('show');

        /*
        |--------------------------------------------------------------------------
        | DELETE zzgo/sys-db-table-definitions/<sysDbTableDefinition>
        |--------------------------------------------------------------------------
        */

        Route::delete('/', ['uses' => 'SysDbTableDefinitionController@destroy'])->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/materialize
        |--------------------------------------------------------------------------
        */

        Route::post('/materialize', ['uses' => 'SysDbTableDefinitionController@materialize'])->name('materialize');

        /*
        |--------------------------------------------------------------------------
        | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/link
        |--------------------------------------------------------------------------
        */

        Route::post('/link', ['uses' => 'SysDbTableDefinitionController@link'])->name('link');

        /*
        |--------------------------------------------------------------------------
        | zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/*
        |--------------------------------------------------------------------------
        */

        Route::group([
                         'prefix' => 'sys-db-field-definitions',
                         'as'     => 'sys-db-field-definitions.',
                     ], function () {

            /*
            |--------------------------------------------------------------------------
            | GET zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/
            |--------------------------------------------------------------------------
            */

            Route::get('/', ['uses' => 'SysDbFieldDefinitionController@index'])->name('index');

            /*
            |--------------------------------------------------------------------------
            | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/
            |--------------------------------------------------------------------------
            */

            Route::post('/', ['uses' => 'SysDbFieldDefinitionController@store'])->name('store');

            /*
            |--------------------------------------------------------------------------
            | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/<sysDbFieldDefinition>/*
            |--------------------------------------------------------------------------
             */

            Route::group([
                             'prefix' => '{sysDbFieldDefinition}',
                         ], function () {

                /*
                |--------------------------------------------------------------------------
                | GET zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/<sysDbFieldDefinition>
                |--------------------------------------------------------------------------
                 */

                Route::get('/', ['uses' => 'SysDbFieldDefinitionController@show'])->name('show');

                /*
                |--------------------------------------------------------------------------
                | DELETE zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/<sysDbFieldDefinition>
                |--------------------------------------------------------------------------
                 */

                Route::delete('/', ['uses' => 'SysDbFieldDefinitionController@destroy'])->name('destroy');

                /*
                |--------------------------------------------------------------------------
                | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/<sysDbFieldDefinition>/link
                |--------------------------------------------------------------------------
                 */

                Route::post('/link', ['uses' => 'SysDbFieldDefinitionController@link'])->name('link');

            });
        });
    });
});

