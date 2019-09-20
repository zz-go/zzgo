<?php
/**
 * @copyright LOOP.
 * @author Manfred John <manfred.john@agentur-loop.com>
 */

namespace ZZGo\Generator;

use ZZGo\Models\SysDbTableDefinition;

/**
 * Class Migration
 *
 * @package ZZGo\Migration
 */
class Model extends Base
{
    /**
     * Base name of the table
     *
     * @var string
     */
    protected $modelName;

    /**
     * Migration constructor.
     *
     * @param string|SysDbTableDefinition $model
     */
    public function __construct($model)
    {
        $inputName       = $model instanceof SysDbTableDefinition ? $model->name : $model;
        $this->modelName = $inputName;

        parent::__construct(ucfirst($this->modelName), "App\Models");

        //Model extends base model
        $this->namespace->addUse("Illuminate\Database\Eloquent\Model");
        $this->class->setExtends("Illuminate\Database\Eloquent\Model");

        //If object was initialized with SysDbTableDefinition - apply all fields
        If ($model instanceof SysDbTableDefinition) {
            $fillables = [];
            /* @var SysDbTableDefinition $sysDbFieldDefinition */
            foreach ($model->sysDbFieldDefinitions as $sysDbFieldDefinition) {
                $fillables [] = $sysDbFieldDefinition->name;
            }
            $this->setFillable($fillables);

            //Add timestamps if active
            $this->setUseTimestamps($model->use_timestamps);

            //Set if table has soft delete
            if ($model->use_soft_deletes) $this->setUseSoftDeletes();
        }

        return $this;
    }

    /**
     * Activate soft deletes for model
     */
    public function setUseSoftDeletes()
    {
        $this->namespace->addUse("Illuminate\Database\Eloquent\SoftDeletes");
        $this->class->addTrait("Illuminate\Database\Eloquent\SoftDeletes");

        return $this;
    }


    /**
     * @param bool $isActive
     */
    public function setUseTimestamps(bool $isActive)
    {
        $this->class->addProperty("timestamps", $isActive);
    }

    /**
     * Define fillable variables
     *
     * @param array $fillable
     */
    public function setFillable(array $fillable)
    {
        $fillableProperty = $this->class->addProperty("fillable", $fillable);
        $fillableProperty->setVisibility("protected");
    }

    /**
     * Write migration to disk
     */
    public function materialize()
    {
        //Define filename of output
        $this->targetFile = app_path() . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR
            . ucfirst($this->modelName) . '.php';

        parent::materialize();
    }
}
