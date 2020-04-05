<?php
/**
 * @author Manfred John <zzgo@mave.at>
 */

namespace ZZGo\Generator;

use Illuminate\Support\Str;
use ZZGo\Models\SysDbTableDefinition;

/**
 * Class Relation
 *
 * @package ZZGo\Generator
 */
class Constraint extends Base
{
    /**
     * Prefix for all stubs used in this class
     */
    const STUB_FOLDER = "constraint";

    /**
     * Prefix used for functions used for adding options
     */
    const STUB_FNC_PREFIX = "addStub";

    /**
     * Supported delete options
     *
     * @var string[]
     */
    static $onDeleteOptions = [
        'set null',
        'cascade',
        'restrict',
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

        parent::__construct("Add" . ucfirst($this->className) . "Constraints");

        //Default use for constraint migrations
        $this->file->addUse("Illuminate\Support\Facades\Schema");
        $this->file->addUse("Illuminate\Database\Schema\Blueprint");
        $this->file->addUse("Illuminate\Database\Migrations\Migration");

        $this->class->setExtends('Migration');

        //Add default methods in migrations
        $this->addMethod('up');
        $this->addMethod('down');

        //If object was initialized with SysDbTableDefinition - apply all fields
        If ($table instanceof SysDbTableDefinition) {

            //Generate relations
            foreach ($table->sysDbRelatedTables as $sysDbRelatedTable) {
                $this->addRelation($sysDbRelatedTable->type,
                                   $sysDbRelatedTable->sysDbTargetTableDefinition);
            }
        }

        return $this;
    }


    /**
     * @param string $type
     * @param SysDbTableDefinition $targetTable
     * @param string $targetField
     * @param string $onDelete
     * @throws \Exception
     */
    public function addRelation(string $type,
                                SysDbTableDefinition $targetTable,
                                string $targetField = "id",
                                string $onDelete = 'cascade')
    {
        if (array_key_exists($onDelete, self::$onDeleteOptions)) {
            throw new \Exception("'$onDelete' is no valid option for onDelete");
        }

        $this->relations[] = [
            "type"        => $type,
            "name"        => $this->createIndexName("foreign", [$targetField]),
            "targetTable" => $targetTable->getSqlName(),
            "targetField" => $targetField,
            "foreignKey"  => $targetTable->name . "_" . $targetField,
            "onDelete"    => $onDelete,
            "template"    => "relation",
        ];
    }


    /**
     * Write migration to disk (only constraints)
     */
    public function materialize()
    {
        //Generate body of up-method
        $this->methods['up']->addBody(' Schema::table(?, function (Blueprint $table) {', [$this->tableName]);
        //Add relations to body
        foreach ($this->relations as $relation) {
            $this->methods['up']->addBody($this->{self::STUB_FNC_PREFIX . ucfirst($relation["type"])}($relation));
        }
        $this->methods['up']->addBody('});');

        //Generate body of down-method
        $this->methods['down']->addBody(' Schema::table(?, function (Blueprint $table) {', [$this->tableName]);
        //Add drop relations to body
        foreach ($this->relations as $relation) {
            $this->methods['down']->addBody($this->{self::STUB_FNC_PREFIX . ucfirst($relation["type"])}(
                ["template" => "drop.relation",
                 "type"     => $relation["type"],
                 "name"     => $relation["name"],
                ]));
        }
        $this->methods['down']->addBody('});');


        //Define filename of output
        $this->targetFile = database_path() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR
            . $this->getDatePrefix() . '_' . "add_{$this->tableName}_constraints" . '.php';

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

        return null;
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
