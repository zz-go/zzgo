<?php
/**
 * @copyright LOOP.
 * @author Manfred John <manfred.john@agentur-loop.com>
 */

namespace ZZGo\Generator;


use Illuminate\Support\Str;
use ZZGo\Models\SysDbTableDefinition;


/**
 * Class Migration
 *
 * @package ZZGo\Migration
 */
class Controller extends Base
{
    /**
     * Base name of the table
     *
     * @var string
     */
    protected $modelName;

    /**
     * @var Route[]
     */
    protected $routes = [];


    /**
     * Migration constructor.
     *
     * @param string|SysDbTableDefinition $model
     */
    public function __construct($model)
    {
        $inputName       = $model instanceof SysDbTableDefinition ? $model->name : $model;
        $this->modelName = ucfirst($inputName);

        parent::__construct($this->modelName . "Controller", "App\Http\Controllers\API");

        //Model extends base model
        $this->namespace->addUse('App\\Models\\' . $this->modelName);
        $this->namespace->addUse("App\Http\Controllers\Controller");
        $this->namespace->addUse("Illuminate\Http\Request");
        $this->class->setExtends("App\Http\Controllers\Controller");

        $this->addDefaultRoutes();

        return $this;
    }


    /**
     * Write migration to disk
     */
    public function materialize()
    {
        //Define filename of output
        $this->targetFile = app_path()
            . DIRECTORY_SEPARATOR . 'Http'
            . DIRECTORY_SEPARATOR . 'Controllers'
            . DIRECTORY_SEPARATOR . 'API'
            . DIRECTORY_SEPARATOR . $this->modelName . 'Controller.php';

        parent::materialize();

        //Add routing for controller
        $this->materializeModelRoutes();
        $this->activateModelRoutes();
    }

    /**
     * Add default routes to controller
     */
    protected function addDefaultRoutes()
    {
        //GET list
        $route          = new Route(
            "get",
            str::plural(strtolower($this->modelName)),
            $this->modelName . "Controller",
            "list");
        $this->routes[] = $route;

        $this->class->addMethod("list")
                    ->addComment("List all " . str::plural(strtolower($this->modelName)))
                    ->addComment("")
                    ->addComment("@return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response")
                    ->setBody('return response(' . $this->modelName . '::all());');


        //GET single
        $route          = new Route(
            "get",
            str::plural(strtolower($this->modelName)) . "/{" . strtolower($this->modelName) . "}",
            $this->modelName . "Controller",
            "get");
        $this->routes[] = $route;

        $this->class->addMethod("get")
                    ->addComment("Show single " . strtolower($this->modelName))
                    ->addComment("")
                    ->addComment("@param {$this->modelName} \$" . strtolower($this->modelName))
                    ->addComment("@return {$this->modelName}")
                    ->setBody('return $' . strtolower($this->modelName) . ';')
                    ->addParameter(strtolower($this->modelName))->setTypeHint('App\\Models\\' . $this->modelName);

        //POST
        $route          = new Route(
            "post",
            str::plural(strtolower($this->modelName)) . "/",
            $this->modelName . "Controller",
            "post");
        $this->routes[] = $route;

        $this->class->addMethod("post")
                    ->addComment("Create new " . strtolower($this->modelName))
                    ->addComment("")
                    ->addComment("@param Request \$request")
                    ->addComment("@return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response")
                    ->setBody("{$this->modelName}::create(\$request->all());\nreturn response(null, 204);")
                    ->addParameter("request")->setTypeHint('Illuminate\Http\Request');

        //DELETE single
        $route          = new Route(
            "delete",
            str::plural(strtolower($this->modelName)) . "/{" . strtolower($this->modelName) . "}",
            $this->modelName . "Controller",
            "delete");
        $this->routes[] = $route;

        $this->class->addMethod("delete")
                    ->addComment("Delete " . strtolower($this->modelName))
                    ->addComment("")
                    ->addComment("@param {$this->modelName} \$" . strtolower($this->modelName))
                    ->addComment("@return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response")
                    ->addComment("@throws \Exception")
                    ->setBody("\$" . strtolower($this->modelName) . "->delete();\nreturn response(null, 204);")
                    ->addParameter(strtolower($this->modelName))->setTypeHint('App\\Models\\' . $this->modelName);
    }


    /**
     * Generates routes defined by this controller
     */
    protected function materializeModelRoutes()
    {
        //ACTIVATE model
        $modelApiFile = base_path()
            . DIRECTORY_SEPARATOR . 'routes'
            . DIRECTORY_SEPARATOR . 'zzgo'
            . DIRECTORY_SEPARATOR . $this->modelName . '.php';

        //Check if parent directory exists. If not, create it
        $targetDirectory = dirname($modelApiFile);
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        file_put_contents($modelApiFile,
                          "<?php\n"
                          . "/**\n * This file is auto-generated.\n */"
                          . "\n\n"
                          . "Route::group([\n"
                          . "                 'namespace' => 'API',\n"
                          . "             ], function () {\n"
                          . implode("\n\n", $this->routes)
                          . "});"
        );
    }


    /**
     * Include model-routes in zzgo-api file
     */
    protected function activateModelRoutes()
    {
        $this->activateZZGoApi();
        $zzgoApiFile = base_path()
            . DIRECTORY_SEPARATOR . 'routes'
            . DIRECTORY_SEPARATOR . 'api_zzgo.php';

        if (strpos(file_get_contents($zzgoApiFile), "/zzgo/{$this->modelName}.php") === false) {
            file_put_contents($zzgoApiFile, "\ninclude_once(__DIR__ . '/zzgo/{$this->modelName}.php');",
                              FILE_APPEND | LOCK_EX);
        }
    }

}
