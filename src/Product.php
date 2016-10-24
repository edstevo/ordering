<?php

namespace App\Models;

use EdStevo\Ordering\Contracts\CanBeOrdered as CanBeOrderedContract;
use EdStevo\Ordering\Helpers\CanBeOrdered;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements CanBeOrderedContract
{
    use CanBeOrdered;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["code", "price", "description"];

    /**
     * The rules associated with the attributes for this model
     *
     * @var array
     */
    public function rules()
    {
        return [
			'code' => 'required|string',
			'price' => 'required|decimal',
			'description' => 'required|string'
		];
    }

    /**
     * Get the id of the order item
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the name of the order item
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->code;
    }

    /**
     * Get the price of the order item
     *
     * @return float
     */
    public function getPrice() : float
    {
        return $this->price;
    }

    /**
     * Get the description of the order item
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }
}