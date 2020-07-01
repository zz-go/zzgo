<?php

namespace ZZGo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class SysDbFieldDefinitionCollection
 *
 * @package ZZGo\Http\Resources
 */
class SysDbFieldDefinitionResourceCollection extends ResourceCollection
{
    /**
     * @var string
     */
    protected $parentUrl = null;

    /**
     * @param string $parentUrl
     * @return $this
     */
    public function setParentUrl(string $parentUrl): SysDbFieldDefinitionResourceCollection
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
                'self'    => $this->parentUrl . '/relationships/sys-db-field-definitions',
                'related' => $this->parentUrl . '/sys-db-field-definitions',
            ];
        } else {
            $retVal['links'] = ['self' => route('zzgo.api.sys-db-field-definitions.index')];
        }

        return $retVal;
    }
}
