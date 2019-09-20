<?php
/**
 * @copyright LOOP.
 * @author Manfred John <manfred.john@agentur-loop.com>
 */

namespace ZZGo\Generator;

use Illuminate\Support\Str;
use Nette\PhpGenerator\PhpLiteral;
use ZZGo\Models\SysDbTableDefinition;

/**
 * Class Migration
 *
 * @package ZZGo\Migration
 */
class Migration extends Base
{
    /**
     * Prefix used for functions used for adding options
     */
    const OPTION_FNC_PREFIX = "optionFnc";

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
        If ($table instanceof SysDbTableDefinition) {

            //Add id by default
            $this->addField(["name" => "id",
                             "type" => "bigIncrements",
                            ]);

            /* @var SysDbTableDefinition $sysDbFieldDefinition */
            foreach ($table->sysDbFieldDefinitions as $sysDbFieldDefinition) {
                $this->addField(["name"     => $sysDbFieldDefinition->name,
                                 "type"     => $sysDbFieldDefinition->type,
                                 "index"    => $sysDbFieldDefinition->index,
                                 "unsigned" => $sysDbFieldDefinition->unsigned,
                                 "default"  => $sysDbFieldDefinition->default,
                                ]);
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
        $this->fields[$function] = ["type" => $function, "name" => new PhpLiteral('')];
    }


    /**
     * Write migration to disk
     */
    public function materialize()
    {
        //Generate body of up-method
        $this->methods['up']->addBody(' Schema::create(?, function (Blueprint $table) {', [$this->tableName]);
        foreach ($this->fields as $name => $data) {

            //Generate string for additional options
            $optionsString = '';
            foreach ($data as $option => $optionValue) {
                if (method_exists($this, self::OPTION_FNC_PREFIX . ucfirst($option))) {
                    $optionsString .= $this->{self::OPTION_FNC_PREFIX . ucfirst($option)}($optionValue);
                }
            }
            $options = new PhpLiteral($optionsString);


            $this->methods['up']->addBody('    $table->' . $data['type'] . '(?)?;', [$data['name'], $options]);
        }
        $this->methods['up']->addBody('});');

        //Generate body of down-method
        $this->methods['down']->addBody(' Schema::dropIfExists(?);', [$this->tableName]);

        //Define filename of output
        $this->targetFile = database_path() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR
            . $this->getDatePrefix() . '_' . "create_{$this->tableName}_table" . '.php';

        parent::materialize();
    }

    /**
     * Option for field: Adds index to field
     *
     * @param $option
     * @return string
     */
    protected function optionFncIndex($option)
    {
        if ($option) {
            return "->index()";
        }

        return '';
    }

    /**
     * Option for field: Marks field as unsigned
     *
     * @param $option
     * @return string
     */
    protected function optionFncUnsigned($option)
    {
        if ($option) {
            return "->unsigned()";
        }

        return '';
    }

    /**
     * Option for field: Marks field as nullable
     *
     * @param $option
     * @return string
     */
    protected function optionFncNullable($option)
    {
        if ($option) {
            return "->nullable()";
        }

        return '';
    }

    /**
     * Option for field: Defines default-value for field
     *
     * @param $option
     * @return string
     */
    protected function optionFncDefault($option)
    {
        if ($option === true) {
            return "->default(true)";

        } elseif ($option === false) {
            return "->default(false)";

        } elseif (is_numeric($option)) {
            return "->default($option)";

        } elseif ($option === null) {
            return "->default(null)";

        } elseif (is_string($option)) {
            return "->default('$option')";
        }

        return "";
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }
}
