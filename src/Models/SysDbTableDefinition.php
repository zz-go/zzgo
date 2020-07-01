<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * @return HasMany
     */
    public function sysDbFieldDefinitions(): HasMany
    {
        return $this->hasMany(SysDbFieldDefinition::class);
    }

    /**
     * @return HasMany
     */
    public function sysDbRelatedTables(): HasMany
    {
        return $this->hasMany(SysDbRelatedTable::class, 'sys_db_source_table_definition_id');
    }

    /**
     * Returns the name the the SQL-table will finally have
     *
     * @return string
     */
    public function getSqlName(): string
    {
        return Str::snake(Str::pluralStudly(class_basename($this->name)));
    }

    /**
     * Get the JSON schema of this model
     *
     * @return array
     */
    public static function getSchema()
    {
        return [
            '$id'         => route('zzgo.api.sys-db-table-definitions.schema'),
            '$schema'     => 'http://json-schema.org/draft-07/schema#',
            'title'       => 'SysDbTableDefinition',
            'description' => 'Definition of tables in application',
            'type'        => 'object',
            'required'    => ['name'],
            'properties'  => [
                'id'               => [
                    'title'       => 'ID',
                    'type'        => 'integer',
                    "readOnly"    => true,
                    'description' => 'ID of the table definition',
                ],
                'name'             => [
                    'title'       => 'Name',
                    'type'        => 'string',
                    'description' => 'Base name of the table',
                ],
                'use_timestamps'   => [
                    'title'       => 'Use Timestamps',
                    'type'        => 'boolean',
                    'default'     => true,
                    'description' => 'Use timestamp columns in table',
                ],
                'use_soft_deletes' => [
                    'title'       => 'Use Soft Deletes',
                    'type'        => 'boolean',
                    'default'     => true,
                    'description' => 'Use soft delete feature',
                ],
                'created_at'       => [
                    'title'       => 'Creation Date',
                    'type'        => 'string',
                    'format'      => 'date-time',
                    "readOnly"    => true,
                    'description' => 'Date/Time when object was created',
                ],
                'updated_at'       => [
                    'title'       => 'Update Date',
                    'type'        => 'string',
                    'format'      => 'date-time',
                    "readOnly"    => true,
                    'description' => 'Date/Time when object was last updated',
                ],
            ],
        ];
    }
}
