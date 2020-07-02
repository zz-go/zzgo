<?php

namespace ZZGo\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use ZZGo\Models\SysDbRelatedTable;

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
 * @property Collection|SysDbFieldDefinitionResource[] sysDbFieldDefinitions
 * @property Collection|SysDbRelatedTable[] sysDbRelatedTables
 *
 * @package ZZGo\Http\Resources
 */
class SysDbTableDefinitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $self   = route('zzgo.api.sys-db-table-definitions.show', ['sysDbTableDefinition' => $this->id]);
        $schema = route('zzgo.api.sys-db-table-definitions.schema');

        return [
            'type'          => 'sys-db-table-definitions',
            'id'            => (string)$this->id,
            'attributes'    => [
                'name'             => $this->name,
                'use_soft_deletes' => $this->use_soft_deletes,
                'use_timestamps'   => $this->use_timestamps,
                'created_at'       => $this->created_at->toISOString(),
                'updated_at'       => $this->updated_at->toISOString(),
            ],
            'relationships' => [
                'sys-db-field-definitions' =>
                    (new SysDbFieldDefinitionResourceCollection($this->whenLoaded('sysDbFieldDefinitions')))
                        ->setParentUrl($self),
            ],
            'links'         => [
                'self'   => $self,
                'schema' => $schema,
            ],
        ];
    }
}
