<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataDefinition extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'type', 'index', 'unsigned', 'nullable', 'default'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zz_datadefinitions';

}