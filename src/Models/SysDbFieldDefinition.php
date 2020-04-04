<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SysDbFieldDefinition
 *
 * @property int id
 * @property int sys_db_table_definition_id
 * @property string name
 * @property string type
 * @property string index
 * @property boolean unsigned
 * @property boolean nullable
 * @property string default
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property SysDbTableDefinition sys_db_table_definition
 *
 * @package ZZGo\Models
 */
class SysDbFieldDefinition extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'index', 'unsigned', 'nullable', 'default', 'sys_db_table_definition_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sys_db_table_definition()
    {
        return $this->belongsTo(SysDbTableDefinition::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }
}
