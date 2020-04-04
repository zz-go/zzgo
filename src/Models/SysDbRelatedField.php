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
 * @property int sys_db_source_field_definition_id
 * @property int sys_db_target_field_definition_id
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property SysDbFieldDefinition sysDbSourceFieldDefinition
 * @property SysDbFieldDefinition sysDbTargetFieldDefinition
 *
 * @package ZZGo\Models
 */
class SysDbRelatedField extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'on_delete'];

    /**
     * @var array
     */
    protected $with = ['sysDbSourceFieldDefinition', 'sysDbTargetFieldDefinition'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sysDbSourceFieldDefinition()
    {
        return $this->hasOne(SysDbFieldDefinition::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sysDbTargetFieldDefinition()
    {
        return $this->hasOne(SysDbFieldDefinition::class);
    }
}
