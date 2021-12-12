<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Http\Controllers;

use App\Http\Controllers\Controller;
use ZZGo\Http\Resources\SysDbFieldDefinitionResource;
use ZZGo\Http\Resources\SysDbFieldDefinitionResourceCollection;
use ZZGo\Models\SysDbFieldDefinition;
use Illuminate\Http\Request;
use ZZGo\Models\SysDbTableDefinition;

/**
 * Class ChairController
 *
 * @package ZZGo\Http\Controllers
 */
class SysDbFieldDefinitionController extends Controller
{
    /**
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @return SysDbFieldDefinitionResourceCollection
     */
    public function index(SysDbTableDefinition $sysDbTableDefinition)
    {
        return (new SysDbFieldDefinitionResourceCollection($sysDbTableDefinition->sysDbFieldDefinitions))
            ->setParentUrl(route('zzgo.api.sys-db-table-definitions.show',
                                 ['sysDbTableDefinition' => $sysDbTableDefinition->id]));
    }

    /**
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @param SysDbFieldDefinition $sysDbFieldDefinition
     * @return SysDbFieldDefinitionResource
     */
    public function show(SysDbTableDefinition $sysDbTableDefinition, SysDbFieldDefinition $sysDbFieldDefinition)
    {
        if (!$sysDbTableDefinition->sysDbFieldDefinitions()
                                  ->where($sysDbFieldDefinition->getKeyName(), $sysDbFieldDefinition->getKey())
                                  ->exists()) {
            abort(404);
        }

        return new SysDbFieldDefinitionResource($sysDbFieldDefinition);
    }

    /**
     * Update SysDbFieldDefinition
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @param Request $request
     * @return SysDbFieldDefinitionResource
     */
    public function update(SysDbTableDefinition $sysDbTableDefinition,
                           SysDbFieldDefinition $sysDbFieldDefinition,
                           Request              $request)
    {
        $requestData = $request->post();
        $sysDbFieldDefinition->update($requestData);

        return new SysDbFieldDefinitionResource($sysDbFieldDefinition);
    }


    /**
     * Attach new SysDbFieldDefinition to SysDbTableDefinition
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @param Request $request
     * @return SysDbFieldDefinitionResource
     */
    public function store(SysDbTableDefinition $sysDbTableDefinition, Request $request)
    {

        $requestData                               = $request->post();
        $requestData['sys_db_table_definition_id'] = $sysDbTableDefinition->id;

        $sysDbFieldDefinition = SysDbFieldDefinition::create($requestData);

        return new SysDbFieldDefinitionResource($sysDbFieldDefinition);
    }


    /**
     * Delete SysDbFieldDefinition
     *
     * @param SysDbFieldDefinition $sysDbFieldDefinition
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(SysDbFieldDefinition $sysDbFieldDefinition)
    {
        $sysDbFieldDefinition->delete();

        return response()->json(null, 204);
    }

    /**
     * Get JSON schema of model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function schema()
    {
        return response()->json(SysDbFieldDefinition::getSchema(), 200);
    }


    /**
     * Link to other field //TODO: Implement Me
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @param SysDbFieldDefinition $sysDbFieldDefinition
     * @return \Illuminate\Http\JsonResponse
     */
    public function link(SysDbTableDefinition $sysDbTableDefinition, SysDbFieldDefinition $sysDbFieldDefinition)
    {
        return response()->json(null, 204);
    }
}
