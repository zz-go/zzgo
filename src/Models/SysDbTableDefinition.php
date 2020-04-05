<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class SysDbTableDefinition
 *
 * @property int id
 * @property string name
 * @property bool use_timestamps
 * @property bool use_soft_deletes
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property Collection|SysDbFieldDefinition[] sysDbFieldDefinitions
 * @property Collection|SysDbRelatedTable[] sysDbRelatedTables
 *
 * @method static create(array $attributes)
 *
 * @package ZZGo\Models
 */
class SysDbTableDefinition extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'use_timestamps', 'use_soft_deletes'];

    /**
     * @var array
     */
    protected $with = ['sysDbFieldDefinitions'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sysDbFieldDefinitions()
    {
        return $this->hasMany(SysDbFieldDefinition::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sysDbRelatedTables()
    {
        return $this->hasMany(SysDbRelatedTable::class, 'sys_db_source_table_definition_id');
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

    /**
     * Returns the name the the SQL-table will finally have
     *
     * @return string
     */
    public function getSqlName() {
        return Str::snake(Str::pluralStudly(class_basename($this->name)));
    }
}
