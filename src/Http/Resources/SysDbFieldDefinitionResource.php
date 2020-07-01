<?php

namespace ZZGo\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use ZZGo\Models\SysDbTableDefinition;

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
 * @property SysDbTableDefinitionResource sysDbTableDefinition
 *
 * @package ZZGo\Http\Resources
 */
class SysDbFieldDefinitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $self   = route('zzgo.api.sys-db-table-definitions.sys-db-field-definitions.show',
                        ['sysDbFieldDefinition' => $this->id,
                         'sysDbTableDefinition' => $this->sys_db_table_definition_id]);
        $schema = route('zzgo.api.sys-db-field-definitions.schema');

        return [
            'type'       => 'sys-db-field-definitions',
            'id'         => (string)$this->id,
            'attributes' => [
                'name'       => $this->name,
                'type'       => $this->type,
                'index'      => $this->index,
                'unsigned'   => $this->unsigned,
                'nullable'   => $this->nullable,
                'default'    => $this->default,
                'created_at' => $this->created_at->toISOString(),
                'updated_at' => $this->updated_at->toISOString(),
            ],
            'links'      => [
                'self'   => $self,
                'schema' => $schema,
            ],
        ];
    }
}
