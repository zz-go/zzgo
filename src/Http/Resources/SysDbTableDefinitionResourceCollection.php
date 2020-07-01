<?php

namespace ZZGo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class SysDbTableDefinitionCollection
 *
 * @package ZZGo\Http\Resources
 */
class SysDbTableDefinitionResourceCollection extends ResourceCollection
{
    /**
     * @var string
     */
    protected $parentUrl = null;

    /**
     * @param string $parentUrl
     * @return $this
     */
    public function setParentUrl(string $parentUrl): SysDbTableDefinitionResourceCollection
    {
        $this->parentUrl = $parentUrl;

        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $retVal = ['data' => $this->collection];

        if ($this->parentUrl) {
            $retVal['links'] = [
                'self'    => $this->parentUrl . '/relationships/sys-db-table-definitions',
                'related' => $this->parentUrl . '/sys-db-table-definitions',
            ];
        } else {
            $retVal['links'] = ['self' => route('zzgo.api.sys-db-table-definitions.index')];
        }

        return $retVal;
    }
}
