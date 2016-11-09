<?php

namespace EdStevo\Ordering\Models;

use Illuminate\Database\Eloquent\Model;
use EdStevo\Generators\Contracts\Models\DaoModelContract;

class Country extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable         = ["iso", "name", "nice_name", "iso_3", "ready", "currency_id"];
    public $timestamps          = false;
    protected $primaryKey       = 'iso';
    protected $incrementing     = false;

    /**
     *  Accessors
     */

    /**
     * Get the identifier for this model
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->iso;
    }

    /**
     * The rules associated with the attributes for this model
     *
     * @var array
     */
    public function rules()
    {
        return [
			'code'          => 'string',
			'name'          => 'string',
			'nice_name'     => 'string',
			'iso_3'         => 'string',
			'ready'         => 'boolean',
			'currency_id'   => 'string'
		];
    }
}