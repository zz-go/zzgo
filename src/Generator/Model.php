<?php
/**
 * @author Manfred John <zzgo@mave.at>
 */

namespace ZZGo\Generator;

use ZZGo\Models\SysDbTableDefinition;

/**
 * Class Model
 *
 * @package ZZGo\Generator
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
     * Model constructor.
     *
     * @param $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct($model)
    {
        $inputName       = $model instanceof SysDbTableDefinition ? $model->name : $model;
        $this->modelName = $inputName;

        parent::__construct(ucfirst($this->modelName), "App\Models");

        //Model extends base model
        $this->namespace->addUse("Illuminate\Database\Eloquent\Model");
        $this->class->setExtends("Illuminate\Database\Eloquent\Model");

        //Add comments to model structure
        $this->class->addComment("Class $inputName");
        $this->class->addComment("");
        $this->class->addComment('@method static create(array $attributes)');

        //If object was initialized with SysDbTableDefinition - apply all fields
        If ($model instanceof SysDbTableDefinition) {
            $fillables = [];
            $this->class->addComment("");
            foreach ($model->sysDbFieldDefinitions as $sysDbFieldDefinition) {
                $this->class->addComment("@property {$sysDbFieldDefinition->type} {$sysDbFieldDefinition->name}");
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

        //Add timestamp property to model
        $this->namespace->addUse("Carbon\Carbon");
        $this->class->addComment('@property Carbon deleted_at');

        return $this;
    }


    /**
     * Activate/deactivate timestamps for model
     *
     * @param bool $isActive
     * @return $this
     */
    public function setUseTimestamps(bool $isActive)
    {
        $this->class->addProperty("timestamps", $isActive);

        if ($isActive) {
            //Add timestamp property to model
            $this->namespace->addUse("Carbon\Carbon");
            $this->class->addComment("");
            $this->class->addComment('@property Carbon created_at');
            $this->class->addComment('@property Carbon updated_at');
        }

        return $this;
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
