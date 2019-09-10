<?php

/**
 * This file is auto-generated.
 */

namespace ZZGo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SysDbTableDefinition
 *
 * @package ZZGo\Models
 */
class SysDbTableDefinition extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    protected $with = ['sys_db_field_definitions'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sys_db_field_definitions()
    {
        return $this->hasMany(SysDbFieldDefinition::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }
}
