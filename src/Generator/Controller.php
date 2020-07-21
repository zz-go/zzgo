<?php
/**
 * @author Manfred John <zzgo@mave.at>
 */

namespace ZZGo\Generator;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZZGo\Models\SysDbTableDefinition;


/**
 * Class Controller
 *
 * @package ZZGo\Generator
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
     * Controller constructor.
     *
     * @param $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct($model)
    {
        $inputName       = $model instanceof SysDbTableDefinition ? $model->name : $model;
        $this->modelName = ucfirst($inputName);

        parent::__construct($this->modelName . "Controller", "App\Http\Controllers\API");

        //Model extends base model
        $this->namespace->addUse('App\\Models\\' . $this->modelName);
        $this->namespace->addUse('App\\Http\\Resources\\' . $this->modelName . 'Resource');
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
        $this->disk       = 'controllers';
        $this->targetFile = ucfirst($this->modelName) . 'Controller.php';

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
            str::snake(str::plural($this->modelName), '-'),
            $this->modelName . "Controller",
            "list",
            str::snake(str::plural($this->modelName), '-') . ".list");
        $this->routes[] = $route;

        $this->class->addMethod("list")
                    ->addComment("List all " . str::plural(strtolower($this->modelName)))
                    ->addComment("")
                    ->addComment("@return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response")
                    ->setBody('return response(' . $this->modelName . '::all());');


        //GET single
        $route          = new Route(
            "get",
            str::snake(str::plural($this->modelName), '-') . "/{" . strtolower($this->modelName) . "}",
            $this->modelName . "Controller",
            "get",
            str::snake(str::plural($this->modelName), '-') . ".get");
        $this->routes[] = $route;

        $this->class->addMethod("get")
                    ->addComment("Show single " . strtolower($this->modelName))
                    ->addComment("")
                    ->addComment("@param {$this->modelName} \$" . strtolower($this->modelName))
                    ->addComment("@return {$this->modelName}Resource")
                    ->setBody("return new {$this->modelName}Resource (\$" . strtolower($this->modelName) . ');')
                    ->addParameter(strtolower($this->modelName))->setType('App\\Models\\' . $this->modelName);

        //POST
        $route          = new Route(
            "post",
            str::snake(str::plural($this->modelName), '-') . "/",
            $this->modelName . "Controller",
            "post",
            str::snake(str::plural($this->modelName), '-') . ".post");
        $this->routes[] = $route;

        $this->class->addMethod("post")
                    ->addComment("Create new " . strtolower($this->modelName))
                    ->addComment("")
                    ->addComment("@param Request \$request")
                    ->addComment("@return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response")
                    ->setBody("{$this->modelName}::create(\$request->all());\nreturn response(null, 204);")
                    ->addParameter("request")->setType('Illuminate\Http\Request');

        //DELETE single
        $route          = new Route(
            "delete",
            str::snake(str::plural($this->modelName), '-') . "/{" . strtolower($this->modelName) . "}",
            $this->modelName . "Controller",
            "delete",
            str::snake(str::plural($this->modelName), '-') . ".delete");
        $this->routes[] = $route;

        $this->class->addMethod("delete")
                    ->addComment("Delete " . strtolower($this->modelName))
                    ->addComment("")
                    ->addComment("@param {$this->modelName} \$" . strtolower($this->modelName))
                    ->addComment("@return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response")
                    ->addComment("@throws \Exception")
                    ->setBody("\$" . strtolower($this->modelName) . "->delete();\nreturn response(null, 204);")
                    ->addParameter(strtolower($this->modelName))->setType('App\\Models\\' . $this->modelName);
    }


    /**
     * Generates routes defined by this controller
     */
    protected function materializeModelRoutes()
    {
        //ACTIVATE model
        $this->disk   = 'routes';
        $modelApiFile = $this->modelName . '.php';

        //Check if parent directory exists. If not, create it
        $targetDirectory = dirname($this->targetFile);
        if (!Storage::disk($this->disk)->exists($targetDirectory)) {
            Storage::disk($this->disk)->makeDirectory($targetDirectory);
        }

        Storage::disk($this->disk)->put($modelApiFile,
                                        "<?php\n"
                                        . "/**\n * This file is auto-generated.\n */"
                                        . "\n\n"
                                        . "Route::group([\n"
                                        . "                 'namespace' => 'API',\n"
                                        . "             ], function () {\n"
                                        . implode("\n\n", $this->routes)
                                        . "});");
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
