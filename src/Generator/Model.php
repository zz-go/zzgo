<?php
/**
 * @copyright LOOP.
 * @author Manfred John <manfred.john@agentur-loop.com>
 */

namespace ZZGo\Generator;

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
     * @param $modelName
     */
    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;

        parent::__construct(ucfirst($modelName), "App\Models");

        //Model extends base model
        $this->namespace->addUse("Illuminate\Database\Eloquent\Model");
        $this->class->setExtends("Illuminate\Database\Eloquent\Model");
    }

    /**
     * Activate soft deletes for model
     */
    public function setUseSoftDeletes()
    {
        $this->namespace->addUse("Illuminate\Database\Eloquent\SoftDeletes");
        $this->class->addTrait("Illuminate\Database\Eloquent\SoftDeletes");
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