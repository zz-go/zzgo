<?php
/**
 * @author Manfred John <zzgo@mave.at>
 */

namespace ZZGo\Generator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ZZGo\Models\SysDbTableDefinition;

/**
 * Class Migration
 *
 * @package ZZGo\Migration
 */
class Migration extends Base
{
    /**
     * Prefix for all stubs used in this class
     */
    const STUB_FOLDER = "migration";

    /**
     * Prefix used for functions used for adding options
     */
    const STUB_FNC_PREFIX = "addStub";


    /**
     * Supported function for migration files
     *
     * @var string[]
     */
    static $availableFunctions = [
        "timestamps",
        "softDeletes",
    ];

    /**
     * Base name of the table
     *
     * @var string
     */
    protected $tableName;

    /**
     * Class name
     *
     * @var string
     */
    protected $className;

    /**
     * Fields added to the migration
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Relations added to the migration
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Migration constructor.
     *
     * @param string|SysDbTableDefinition $table
     * @throws \Exception
     */
    public function __construct($table)
    {
        $inputName       = $table instanceof SysDbTableDefinition ? $table->name : $table;
        $this->tableName = Str::snake(Str::pluralStudly(class_basename($inputName)));
        $this->className = Str::studly($this->tableName);

        parent::__construct("Create" . ucfirst($this->className) . "Table");

        //Default use for migrations
        $this->file->addUse("Illuminate\Support\Facades\Schema");
        $this->file->addUse("Illuminate\Database\Schema\Blueprint");
        $this->file->addUse("Illuminate\Database\Migrations\Migration");

        $this->class->setExtends('Migration');

        //Add default methods in migrations
        $this->addMethod('up');
        $this->addMethod('down');

        //If object was initialized with SysDbTableDefinition - apply all fields
        if ($table instanceof SysDbTableDefinition) {

            //Add id by default
            $this->addField(["name" => "id",
                             "type" => "bigIncrements",
                            ]);

            //Generate field definitions
            foreach ($table->sysDbFieldDefinitions as $sysDbFieldDefinition) {
                $this->addField(["name"     => $sysDbFieldDefinition->name,
                                 "type"     => $sysDbFieldDefinition->type,
                                 "index"    => $sysDbFieldDefinition->index,
                                 "unsigned" => $sysDbFieldDefinition->unsigned,
                                 "default"  => $sysDbFieldDefinition->default,
                                 "nullable" => $sysDbFieldDefinition->nullable,
                                ]);
            }

            //Generate relations
            foreach ($table->sysDbRelatedTables as $sysDbRelatedTable) {
                $this->addRelatedTable($sysDbRelatedTable->type,
                                       $sysDbRelatedTable->sysDbTargetTableDefinition);
            }

            //Add timestamps if active
            if ($table->use_timestamps) $this->addFunction("timestamps");

            //Set if table has soft delete
            if ($table->use_soft_deletes) $this->addFunction("softDeletes");
        }

        return $this;
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function addField($data)
    {
        if (!array_key_exists('name', $data) ||
            !array_key_exists('type', $data)) {
            throw new \Exception("Name / Type are mandatory");
        }

        if (array_key_exists($data['name'], $this->fields)) {
            throw new \Exception("Field {$data['name']} already exists");
        }

        //Check and adept default value based on type of field
        if (array_key_exists("default", $data) && !is_null($data["default"]) &&
            array_key_exists("type", $data)) {
            if (preg_match("/integer/i", $data["type"])) {
                $data["default"] = intval($data["default"]);
            } else if (
                preg_match("/float/i", $data["type"]) ||
                preg_match("/decimal/i", $data["type"]) ||
                preg_match("/double/i", $data["type"])
            ) {
                $data["default"] = floatval($data["default"]);
            } else if (preg_match("/bool/i", $data["type"])) {
                $data["default"] = (bool)$data["default"];
            }
        }

        //Unset null-values (except default)
        foreach ($data as $attribute => $value) {
            if (is_null($value) && $attribute != "default") {
                unset($data[$attribute]);
            }
        }

        //Assign template value and data
        $data["template"]            = "field";
        $this->fields[$data['name']] = $data;
    }


    /**
     * Add a function to migration class
     *
     * @param $function
     * @throws \Exception
     */
    public function addFunction($function)
    {
        if (!in_array($function, self::$availableFunctions)) {
            throw new \Exception("Function $function is not available in migrations.");
        }

        //Use function name as "type" and make the "name" empty to generate the correct output
        $this->fields[$function] = ["type" => $function, "template" => "option"];
    }

    /**
     * Add relation to other table
     *
     * @param string $type
     * @param SysDbTableDefinition $targetTable
     * @param string $targetField
     * @throws \Exception
     */
    public function addRelatedTable(string $type, SysDbTableDefinition $targetTable, string $targetField = "id")
    {
        //TODO: Implement all relationship types
        switch ($type) {
            case "belongsTo":
                $this->addField(["name"     => $targetTable->name . "_" . $targetField,
                                 "type"     => "bigInteger",
                                 "unsigned" => true,
                                ]);
        }
    }

    /**
     * Write migration to disk (without constraints)
     */
    public function materialize()
    {
        //Generate body of up-method
        $this->methods['up']->addBody(' Schema::create(?, function (Blueprint $table) {', [$this->tableName]);
        foreach ($this->fields as $field) {
            $this->methods['up']->addBody($this->{self::STUB_FNC_PREFIX . ucfirst($field["type"])}($field));
        }

        $this->methods['up']->addBody('});');

        //Generate body of down-method
        $this->methods['down']->addBody(' Schema::dropIfExists(?);', [$this->tableName]);


        //Delete migration file if already created in the past
        $this->disk      = 'migrations';
        foreach (Storage::disk($this->disk)->files() as $migrationFile) {
            if (preg_match("/create_{$this->tableName}_table/", $migrationFile)) {
                $this->targetFile = $migrationFile;
                break;
            }
        }

        //Define new filename of output
        $this->targetFile = $this->targetFile ?? $this->getDatePrefix() . '_' . "create_{$this->tableName}_table.php";

        parent::materialize();
    }


    /**
     * @param $name
     * @param $arguments
     * @return string|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __call($name, $arguments)
    {
        //Add content via stub file
        if (preg_match("/^" . self::STUB_FNC_PREFIX . "/", $name) &&
            is_array($arguments) && array_key_exists(0, $arguments) &&
            is_array($arguments[0]) && array_key_exists("template", $arguments[0])
            && array_key_exists("template", $arguments[0])) {

            $template = $arguments[0]["template"];
            $type     = $arguments[0]["type"];
            unset($arguments[0]["template"]);
            unset($arguments[0]["type"]);
            ksort($arguments[0]);
            if ($variant = implode(".", array_keys($arguments[0]))) {
                $variant = ".$variant";
            }

            //Create variable names for placeholder in stub
            $varNames = array_keys($arguments[0]);
            array_unshift($varNames, "type");
            array_walk($varNames, function (&$element) {
                $element = "{{" . $element . "}}";
            });

            //Create variable values
            $varValues = array_values($arguments[0]);
            array_walk($varValues, function (&$element) {
                if (is_null($element)) {
                    //Null as default is a null-string
                    $element = "null";
                } else if (is_string($element)) {
                    //Put character values into double quotes
                    $element = '"' . $element . '"';
                } else if (is_bool($element)) {
                    //Create a string for boolean values
                    $element = $element ? 'true' : 'false';
                }
            });
            array_unshift($varValues, $type);

            return rtrim(str_replace($varNames, $varValues, $this->getStub($template . $variant)));
        }
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
    }

    /**
     * Create name index (from Laravel Framework)
     *
     * @param $type
     * @param array $columns
     * @return string|string[]
     */
    protected function createIndexName($type, array $columns)
    {
        $index = strtolower($this->tableName . '_' . implode('_', $columns) . '_' . $type);

        return str_replace(['-', '.'], '_', $index);
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        //Make sure a new timestamp is used
        sleep(1);

        return date('Y_m_d_His');
    }
}
