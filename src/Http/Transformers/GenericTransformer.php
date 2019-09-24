<?php


namespace ZZGo\Http\Transformers;


use Illuminate\Database\Eloquent\Model;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class GenericTransformer extends TransformerAbstract
{
    protected $object;
    protected $includeObjects = [];

    public function transform(Model $model)
    {
        $this->object = $model;

        $ret = [];
        foreach ($model->getAttributes() as $key => $value) {
            $ret[$key] = $value;
        }

        $object = null;

        return $ret;
    }

    public function processIncludedResources(Scope $scope, $data)
    {
        $includedData = [];

        foreach ($this->object->getRelations() as $include => $relation) {
            $incl_obj = $this->object->{$include};
            if ($incl_obj instanceof \Illuminate\Database\Eloquent\Collection) {
                $resource = new Collection($incl_obj, new GenericTransformer, $include);
            } else {
                $resource = new Item($incl_obj, new GenericTransformer, $include);
            }
            $childScope             = $scope->embedChildScope($include, $resource);
            $includedData[$include] = $childScope->toArray();
        }

        return $includedData === [] ? false : $includedData;
    }

    public function getAvailableIncludes()
    {
        return ["GenericIncludes"];
    }
}
