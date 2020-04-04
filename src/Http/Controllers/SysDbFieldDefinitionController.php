<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Http\Controllers;

use App\Http\Controllers\Controller;
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
     * List all chairs
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(SysDbFieldDefinition::all());
    }


    /**
     * Show single SysDbFieldDefinition
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @param SysDbFieldDefinition $sysDbFieldDefinition
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SysDbTableDefinition $sysDbTableDefinition, SysDbFieldDefinition $sysDbFieldDefinition)
    {
        return response()->json($sysDbFieldDefinition);
    }


    /**
     * Attach new SysDbFieldDefinition to SysDbTableDefinition
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SysDbTableDefinition $sysDbTableDefinition, Request $request)
    {

        $xxx                               = $request->all();
        $xxx['sys_db_table_definition_id'] = $sysDbTableDefinition->id;

        SysDbFieldDefinition::create($xxx);

        return response()->json(null, 204);
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
