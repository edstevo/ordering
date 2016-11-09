<?php

namespace EdStevo\Ordering\Models;

use Illuminate\Database\Eloquent\Model;
use EdStevo\Generators\Contracts\Models\DaoModelContract;

class Currency extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable         = ["code", "name", "symbol"];
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
        return $this->id;
    }

    /**
     * The rules associated with the attributes for this model
     *
     * @var array
     */
    public function rules()
    {
        return [
			'code' => 'required|string',
			'name' => 'required|string',
			'symbol' => 'required|string'
		];
    }
}