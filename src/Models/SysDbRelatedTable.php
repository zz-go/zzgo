<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SysDbTableDefinition
 *
 * @property int id
 * @property string name
 * @property string type
 * @property string on_delete
 * @property int sys_db_source_table_definition_id
 * @property int sys_db_target_table_definition_id
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property SysDbTableDefinition sysDbSourceTableDefinition
 * @property SysDbTableDefinition sysDbTargetTableDefinition
 *
 * @package ZZGo\Models
 */
class SysDbRelatedTable extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'on_delete', 'sys_db_source_table_definition_id', 'sys_db_target_table_definition_id'];

    /**
     * @var array
     */
    protected $with = ['sysDbSourceTableDefinition', 'sysDbTargetTableDefinition'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sysDbSourceTableDefinition()
    {
        return $this->belongsTo(SysDbTableDefinition::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sysDbTargetTableDefinition()
    {
        return $this->belongsTo(SysDbTableDefinition::class);
    }
}
