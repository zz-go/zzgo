<?php

use Illuminate\Support\Facades\Route;
use ZZGo\Http\Controllers\SysDbTableDefinitionController;
use ZZGo\Http\Controllers\SysDbFieldDefinitionController;

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

Route::get('sys-db-table-definitions/schema.json', [SysDbTableDefinitionController::class, 'schema'])->name('sys-db-table-definitions.schema');
Route::get('sys-db-field-definitions/schema.json', [SysDbFieldDefinitionController::class, 'schema'])->name('sys-db-field-definitions.schema');

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
    Route::get('/', [SysDbTableDefinitionController::class, 'index'])->name('index');

    /*
    |--------------------------------------------------------------------------
    | POST zzgo/sys-db-table-definitions/
    |--------------------------------------------------------------------------
    */
    Route::post('/', [SysDbTableDefinitionController::class, 'store'])->name('store');

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

        Route::get('/', [SysDbTableDefinitionController::class, 'show'])->name('show');

        /*
        |--------------------------------------------------------------------------
        | PUT zzgo/sys-db-table-definitions/<sysDbTableDefinition>
        |--------------------------------------------------------------------------
        */

        Route::put('/', [SysDbTableDefinitionController::class, 'update'])->name('update');

        /*
        |--------------------------------------------------------------------------
        | DELETE zzgo/sys-db-table-definitions/<sysDbTableDefinition>
        |--------------------------------------------------------------------------
        */

        Route::delete('/', [SysDbTableDefinitionController::class, 'destroy'])->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/materialize
        |--------------------------------------------------------------------------
        */

        Route::post('/materialize', [SysDbTableDefinitionController::class, 'materialize'])->name('materialize');

        /*
        |--------------------------------------------------------------------------
        | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/link
        |--------------------------------------------------------------------------
        */

        Route::post('/link', [SysDbTableDefinitionController::class, 'link'])->name('link');

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

            Route::get('/', [SysDbFieldDefinitionController::class, 'index'])->name('index');

            /*
            |--------------------------------------------------------------------------
            | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/
            |--------------------------------------------------------------------------
            */

            Route::post('/', [SysDbFieldDefinitionController::class, 'store'])->name('store');

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

                Route::get('/', [SysDbFieldDefinitionController::class, 'show'])->name('show');

                /*
                |--------------------------------------------------------------------------
                | PUT zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/<sysDbFieldDefinition>
                |--------------------------------------------------------------------------
                */

                Route::put('/', [SysDbFieldDefinitionController::class, 'update'])->name('update');

                /*
                |--------------------------------------------------------------------------
                | DELETE zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/<sysDbFieldDefinition>
                |--------------------------------------------------------------------------
                 */

                Route::delete('/', [SysDbFieldDefinitionController::class, 'destroy'])->name('destroy');

                /*
                |--------------------------------------------------------------------------
                | POST zzgo/sys-db-table-definitions/<sysDbTableDefinition>/sys-db-field-definitions/<sysDbFieldDefinition>/link
                |--------------------------------------------------------------------------
                 */

                Route::post('/link', [SysDbFieldDefinitionController::class, 'link'])->name('link');

            });
        });
    });
});

