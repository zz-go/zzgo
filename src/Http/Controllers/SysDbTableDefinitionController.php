<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Http\Controllers;

use App\Http\Controllers\Controller;
use ZZGo\Generator\Migration;
use ZZGo\Models\SysDbTableDefinition;
use Illuminate\Http\Request;

/**
 * Class ChairController
 *
 * @package ZZGo\Http\Controllers
 */
class SysDbTableDefinitionController extends Controller
{
    /**
     * List all chairs
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(SysDbTableDefinition::all());
    }

    /**
     * Show single SysDbTableDefinition
     *
     * @param SysDbTableDefinition $SysDbTableDefinition
     * @return mixed
     */
    public function show(SysDbTableDefinition $sysDbTableDefinition)
    {
        return response()->json($sysDbTableDefinition);
    }


    /**
     * Create new SysDbTableDefinition
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        SysDbTableDefinition::create($request->all());

        return response()->json(null, 204);
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
        }

        return response()->json(null, 204);
    }
}
