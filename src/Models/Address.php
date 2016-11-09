<?php

namespace EdStevo\Ordering\Models;

use Illuminate\Database\Eloquent\Model;
use EdStevo\Generators\Contracts\Models\DaoModelContract;

class Address extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["address_1", "address_2", "address_3", "city", "post_code", "country_id"];
    public $timestamps  = false;

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
			'address_1' => 'required|string',
			'address_2' => 'string',
			'address_3' => 'string',
			'city'      => 'required|string',
			'post_code' => 'required|string',
			'country'   => 'required|string'
		];
    }
}