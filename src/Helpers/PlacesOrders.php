<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Helpers;

use EdStevo\Ordering\Models\Order;

trait PlacesOrders
{

    /**
     * Define the relationship between the customer and the orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Expressive way for a customer to place an order
     *
     * @return \EdStevo\Ordering\Models\Order
     */
    public function placeOrder() : Order
    {
        $data   = [
            'email' => $this->getEmail(),
            'tel'   => $this->getTel()
        ];

        return $this->orders()->create($data);
    }
}