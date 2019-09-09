<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Http\Controllers;

use App\Http\Controllers\Controller;
use ZZGo\Models\DataDefinition;
use Illuminate\Http\Request;

/**
 * Class ChairController
 *
 * @package ZZGo\Http\Controllers
 */
class DataDefinitionController extends Controller
{
    /**
     * List all chairs
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(DataDefinition::all());
    }


    /**
     * Show single ZZDataDefinition
     *
     * @param DataDefinition $data_definition
     * @return mixed
     */
    public function show(DataDefinition $data_definition)
    {
        return response()->json($data_definition);
    }


    /**
     * Create new ZZDataDefinition
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DataDefinition::create($request->all());

        return response()->json(null, 204);
    }


    /**
     * Delete ZZDataDefinition
     *
     * @param DataDefinition $data_definition
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(DataDefinition $data_definition)
    {
        $data_definition->delete();

        return response()->json(null, 204);
    }
}
