<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Models;

use EdStevo\Ordering\Contracts\OrderItemContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class OrderItem extends Model implements OrderItemContract
{

    protected $fillable = ['order_item_id', 'order_item_type', 'name', 'description', 'amount'];
    public $timestamps  = false;

    /**
     * Define the morph relationship
     */
    public function item()
    {
        return $this->morphTo();
    }

    /**
     * Get the product that this order item is related to
     *
     * @return mixed
     */
    public function getProduct()
    {
        return app()->make($this->order_item_type)->find($this->order_item_id);
    }
}