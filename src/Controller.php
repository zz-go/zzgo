<?php
/**
 * @copyright LOOP.
 * @author Manfred John <manfred.john@agentur-loop.com>
 */

namespace ZZGo;


use Illuminate\Support\Str;


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
     * @param $modelName
     */
    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;

        parent::__construct(ucfirst($this->modelName) . "Controller", "App\Http\Controllers\API");

        //Model extends base model
        $this->namespace->addUse('App\\Models\\' . ucfirst($modelName));
        $this->namespace->addUse("App\Http\Controllers\Controller");
        $this->class->setExtends("App\Http\Controllers\Controller");

        $this->addDefaultRoutes();

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
            . DIRECTORY_SEPARATOR . ucfirst($this->modelName) . 'Controller.php';

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
        //Get list
        $route          = new Route(
            "get",
            str::plural(strtolower($this->modelName)),
            ucfirst($this->modelName) . "Controller",
            "list");
        $this->routes[] = $route;

        $this->class->addMethod("list")
                    ->setBody('return response(' . ucfirst($this->modelName) . '::all());');


        //Get single
        $route          = new Route(
            "get",
            str::plural(strtolower($this->modelName)) . "/{" . strtolower($this->modelName) . "}",
            ucfirst($this->modelName) . "Controller",
            "get");
        $this->routes[] = $route;

        $this->class->addMethod("get")
                    ->setBody('return $' . strtolower($this->modelName) . ';')
                    ->addParameter($this->modelName)->setTypeHint('App\\Models\\' . ucfirst($this->modelName));
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