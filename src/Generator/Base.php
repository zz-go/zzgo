<?php
/**
 * @copyright LOOP.
 * @author Manfred John <manfred.john@agentur-loop.com>
 */

namespace ZZGo\Generator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;


/**
 * Class Base
 *
 * @package ZZGo
 */
abstract class Base
{
    /**
     * @var PhpFile
     */
    protected $file;

    /**
     * @var PhpNamespace
     */
    protected $namespace;

    /**
     * @var ClassType
     */
    protected $class;

    /**
     * @var Method[]
     */
    protected $methods = [];

    /**
     * @var string
     */
    protected $targetFile;


    /**
     * Base constructor.
     *
     * @param string $className
     * @param string $namespace
     */
    public function __construct(string $className = "", string $namespace = "")
    {
        //Create file
        $this->file = new PhpFile();
        $this->file->addComment('This file is auto-generated.');

        //Create optional namespace and class
        if ($className) {
            if ($namespace) {
                $this->namespace = $this->file->addNamespace($namespace);
                $this->class     = $this->namespace->addClass($className);
            } else {
                $this->class = $this->file->addClass($className);
            }
        }
    }

    /**
     * Add method to class
     *
     * @param string $methodName
     * @return mixed
     */
    public function addMethod(string $methodName)
    {
        if (array_key_exists($methodName, $this->methods)) {
            return $this->methods[$methodName];
        } else {
            $this->methods[$methodName] = $this->class->addMethod($methodName);

            return $this->methods[$methodName];
        }
    }

    /**
     * Generates files defined by this object
     */
    public function materialize()
    {
        if ($this->targetFile) {

            //Check if parent directory exists. If not, create it
            $targetDirectory = dirname($this->targetFile);
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            //Create class file
            file_put_contents($this->targetFile, $this->file);
        }
    }

    /**
     * Prepare Laravel to use ZZGo Routes
     */
    public function activateZZGoApi()
    {
        //Prepare zzgo-api
        $zzgoApiFile = base_path()
            . DIRECTORY_SEPARATOR . 'routes'
            . DIRECTORY_SEPARATOR . 'api_zzgo.php';

        if (!is_file($zzgoApiFile)) {
            file_put_contents($zzgoApiFile,
                              "<?php\n"
                              . "/**\n * This file is auto-generated.\n */"
                              . "\n\n"
            );
        }

        //Add zzgo-api file to laravel api
        $apiFile = base_path()
            . DIRECTORY_SEPARATOR . 'routes'
            . DIRECTORY_SEPARATOR . 'api.php';

        if (strpos(file_get_contents($apiFile), "api_zzgo.php") === false) {
            file_put_contents($apiFile, "\n\ninclude_once(__DIR__ . '/api_zzgo.php');",
                              FILE_APPEND | LOCK_EX);
        }
    }
}