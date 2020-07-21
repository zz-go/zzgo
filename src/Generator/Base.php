<?php
/**
 * @author Manfred John <zzgo@mave.at>
 */

namespace ZZGo\Generator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
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
    protected $disk = 'base_path';

    /**
     * @var string
     */
    protected $targetFile;

    /**
     * @var Filesystem
     */
    protected $filesystem;


    /**
     * Base constructor.
     *
     * @param string $className
     * @param string $namespace
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $className = "", string $namespace = "")
    {
        $this->filesystem = app()->make(Filesystem::class);

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
            if (!Storage::disk($this->disk)->exists($targetDirectory)) {
                Storage::disk($this->disk)->makeDirectory($targetDirectory);
            }

            //Create class file
            Storage::disk($this->disk)->put($this->targetFile, $this->file);
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


    /**
     * Get content of stub file
     *
     * @param string $stubName
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub(string $stubName): string
    {
        return $this->filesystem->get(__DIR__ . "/stubs/" . strtolower(static::STUB_FOLDER . "/$stubName.stub"));
    }


    /**
     * Check if stub file exists
     *
     * @param string $stubName
     * @return bool
     */
    protected function getStubExists(string $stubName): bool
    {
        return $this->filesystem->exists(__DIR__ . "/stubs/" . strtolower(static::STUB_FOLDER . "/$stubName.stub"));
    }
}
