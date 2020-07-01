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
 * @property SysDbTableDefinition sysDbTableDefinition
 *
 * @method static create(array $attributes)
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
    public function sysDbTableDefinition()
    {
        return $this->belongsTo(SysDbTableDefinition::class);
    }

    /**
     * Get the JSON schema of this model
     *
     * @return array
     */
    public static function getSchema()
    {
        return [
            '$id'         => route('zzgo.api.sys-db-field-definitions.schema'),
            '$schema'     => 'http://json-schema.org/draft-07/schema#',
            'title'       => 'SysDbFieldDefinition',
            'description' => 'Definition of a column in a table',
            'type'        => 'object',
            'required'    => ['name', 'type'],
            'properties'  => [
                'id'         => [
                    'title'       => 'ID',
                    'type'        => 'integer',
                    "readOnly"    => true,
                    'description' => 'ID of the column definition',
                ],
                'name'       => [
                    'title'       => 'Name',
                    'type'        => 'string',
                    'description' => 'Name of the colum',
                ],
                'type'       => [
                    'title'       => 'Type',
                    'type'        => 'string',
                    'description' => 'Use timestamp columns in table',
                ],
                'index'      => [
                    'title'       => 'Index',
                    'type'        => 'string',
                    'description' => 'Add index to column',
                ],
                'unsigned'   => [
                    'title'       => 'Unsigned',
                    'type'        => 'boolean',
                    'default'     => false,
                    'description' => 'Only valid for numeric columns',
                ],
                'nullable'   => [
                    'title'       => 'Nullable',
                    'type'        => 'boolean',
                    'default'     => false,
                    'description' => 'Defines if a column may be null',
                ],
                'default'    => [
                    'title'       => 'Default',
                    'type'        => 'string',
                    'default'     => null,
                    'description' => 'Default value for column',
                ],
                'created_at' => [
                    'title'       => 'Creation Date',
                    'type'        => 'string',
                    'format'      => 'date-time',
                    "readOnly"    => true,
                    'description' => 'Date/Time when object was created',
                ],
                'updated_at' => [
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
