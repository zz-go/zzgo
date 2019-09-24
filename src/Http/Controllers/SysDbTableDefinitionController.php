<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\JsonApiSerializer;
use ZZGo\Generator\Controller as GeneratorController;
use ZZGo\Generator\Migration;
use ZZGo\Generator\Model;
use ZZGo\Http\Transformers\GenericTransformer;
use ZZGo\Models\SysDbTableDefinition;
use Illuminate\Http\Request;

/**
 * Class ChairController
 *
 * @package ZZGo\Http\Controllers
 */
class SysDbTableDefinitionController extends Controller
{

    protected $request;
    protected $response;
    protected $manager;


    public function __construct(Request $request)
    {
        $this->request = $request;
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
        return response()->json(SysDbTableDefinition::all());
    }


    /**
     * Show single SysDbTableDefinition
     *
     * @param SysDbTableDefinition $sysDbTableDefinition
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SysDbTableDefinition $sysDbTableDefinition)
    {
        $resouce = new Item($sysDbTableDefinition, new GenericTransformer, get_class($sysDbTableDefinition));
        $data = $this->manager->createData($resouce);
        return $data->toArray();
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
            (new Model($data_definition))->materialize();
            (new GeneratorController($data_definition))->materialize();
        }

        //Execute migrations
        Artisan::call('migrate');

        return response()->json(null, 204);
    }
}
