<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Http\Controllers;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use ZZGo\Http\Transformers\GenericTransformer;
use ZZGo\Models\SysDbFieldDefinition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use ZZGo\Models\SysDbTableDefinition;

/**
 * Class ChairController
 *
 * @package ZZGo\Http\Controllers
 */
class SysDbFieldDefinitionController extends Controller
{

    /**
     * @var Manager
     */
    protected $manager;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new JsonApiSerializer('http://zzgo.local/api'));
    }

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
        $resouce = new Item($sysDbFieldDefinition, new GenericTransformer, get_class($sysDbFieldDefinition));
        $data    = $this->manager->createData($resouce);

        return $data->toArray();
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
}
