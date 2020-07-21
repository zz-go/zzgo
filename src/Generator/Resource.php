<?php
/**
 * @author Manfred John <zzgo@mave.at>
 */

namespace ZZGo\Generator;

use Illuminate\Support\Str;
use ZZGo\Models\SysDbFieldDefinition;
use ZZGo\Models\SysDbTableDefinition;
use Laminas\Code\Generator\ValueGenerator;

/**
 * Class Model
 *
 * @package ZZGo\Generator
 */
class Resource extends Base
{
    /**
     * Base name of the table
     *
     * @var string
     */
    protected $modelName;

    /**
     * Resource constructor.
     *
     * @param $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct($model)
    {
        $inputName       = $model instanceof SysDbTableDefinition ? $model->name : $model;
        $this->modelName = $inputName;

        parent::__construct(ucfirst($this->modelName) . "Resource", "App\Http\Resources");

        //Model extends base model
        $this->namespace->addUse("Illuminate\Http\Resources\Json\JsonResource");
        $this->class->setExtends("Illuminate\Http\Resources\Json\JsonResource");
        $this->namespace->addUse("Illuminate\Http\Request");

        //Add comments to model structure
        $this->class->addComment("Class $inputName");
        $this->class->addComment("");

        //If object was initialized with SysDbTableDefinition - apply all fields
        if ($model instanceof SysDbTableDefinition) {
            $resourceAttributes = [];
            $this->class->addComment("@property int id");
            foreach ($model->sysDbFieldDefinitions as $sysDbFieldDefinition) {
                $this->class->addComment("@property {$sysDbFieldDefinition->type} {$sysDbFieldDefinition->name}");
                $resourceAttributes [$sysDbFieldDefinition->name] = $this->getAttributeValue($sysDbFieldDefinition);
            }

            $returnStructure = $this->getJsonApiStructure($resourceAttributes);
            $this->addToArrayMethod($returnStructure);
        }

        return $this;
    }

    /**
     * @param $returnStructure
     */
    protected function addToArrayMethod($returnStructure)
    {
        $generator = new ValueGenerator($returnStructure, ValueGenerator::TYPE_ARRAY_SHORT);
        $generator->setIndentation('    '); // 4 spaces

        $this->class->addMethod('toArray')
                    ->addComment('Transform the resource into an array.')
                    ->addComment('')
                    ->addComment('@param Request $request')
                    ->addComment('@return array')
                    ->setBody('return ' . $generator->generate() . ';')
                    ->addParameter('request');
    }

    /**
     * @param $resourceAttributes
     * @return array
     */
    protected function getJsonApiStructure($resourceAttributes)
    {
        $routeName = str::snake(str::plural($this->modelName), '-');

        return [
            'type'       => $routeName,
            'id'         => new ValueGenerator('(string)$this->id', ValueGenerator::TYPE_CONSTANT),
            'attributes' => $resourceAttributes,
            'links'      => [
                'self'   => new ValueGenerator("route('{$routeName}.get', ['" . strtolower($this->modelName)
                                               . "' => \$this->id])",
                                               ValueGenerator::TYPE_CONSTANT),
//                'schema' => new ValueGenerator("route('{$routeName}.schema')",
//                                               ValueGenerator::TYPE_CONSTANT),
            ],
        ];
    }

    /**
     * @param SysDbFieldDefinition $sysDbFieldDefinition
     * @return ValueGenerator
     */
    protected function getAttributeValue(SysDbFieldDefinition $sysDbFieldDefinition)
    {
        switch ($sysDbFieldDefinition->type) {
            default:
                return new ValueGenerator('$this->' . $sysDbFieldDefinition->name,
                                          ValueGenerator::TYPE_CONSTANT);
        }
    }

    /**
     * Write migration to disk
     */
    public function materialize()
    {
        //Define filename of output
        $this->disk       = 'resources';
        $this->targetFile =  ucfirst($this->modelName) . 'Resource.php';

        parent::materialize();
    }
}
