<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Http\Controllers;

use App\Http\Controllers\Controller;
use ZZGo\Generator\Constraint;
use ZZGo\Generator\Controller as GeneratorController;
use ZZGo\Generator\Migration;
use ZZGo\Generator\Model;
use ZZGo\Http\Resources\SysDbTableDefinitionResource;
use ZZGo\Http\Resources\SysDbTableDefinitionResourceCollection;
use ZZGo\Models\SysDbRelatedTable;
use ZZGo\Models\SysDbTableDefinition;
use Illuminate\Http\Request;


/**
 * Class SysDbTableDefinitionController
 *
 * @package ZZGo\Http\Controllers
 */
class SysDbTableDefinitionController extends Controller
{
    /**
     * @return SysDbTableDefinitionResourceCollection
     */
    public function index()
    {
        return new SysDbTableDefinitionResourceCollection(SysDbTableDefinition::all());
    }


    /**
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @return SysDbTableDefinitionResource
     */
    public function show(SysDbTableDefinition $sysDbTableDefinition)
    {
        return new SysDbTableDefinitionResource($sysDbTableDefinition);
    }


    /**
     * Create new SysDbTableDefinition
     *
     * @param Request $request
     * @return SysDbTableDefinitionResource
     */
    public function store(Request $request)
    {
        $sysDbTableDefinition = SysDbTableDefinition::create($request->all());

        return new SysDbTableDefinitionResource($sysDbTableDefinition);
    }


    /**
     * Delete SysDbTableDefinition
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(SysDbTableDefinition $sysDbTableDefinition)
    {
        $sysDbTableDefinition->delete();

        return response()->json(null, 204);
    }

    /**
     * Get JSON schema of model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function schema()
    {
        return response()->json(SysDbTableDefinition::getSchema(), 200);
    }

    /**
     * Add related table //TODO: This is a draft
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function link(SysDbTableDefinition $sysDbTableDefinition, Request $request)
    {
        SysDbRelatedTable::create(
            [
                "name"                              => $request->input("name",
                                                                       $sysDbTableDefinition->name . "_" . rand(1000, 9999)),
                "type"                              => $request->input("type"),
                "sys_db_source_table_definition_id" => $sysDbTableDefinition->id,
                "sys_db_target_table_definition_id" => $request->input("target_id"),
                "on_delete"                         => $request->input("on_delete"),
            ]);

        return response()->json(null, 204);
    }


    /**
     * Generate all defined data definitions
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function materialize()
    {
        $data_definitions = SysDbTableDefinition::all();

        foreach ($data_definitions as $data_definition) {
            (new Migration($data_definition))->materialize();
            (new Model($data_definition))->materialize();
            (new GeneratorController($data_definition))->materialize();
        }

        //Add constraints as last step
        foreach ($data_definitions as $data_definition) {
            (new Constraint($data_definition))->materialize();
        }

        return response()->json(null, 204);
    }
}
