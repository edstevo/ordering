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
     * Expressive way for a customer to place an order
     *
     * @param \EdStevo\Ordering\Models\Order $order
     *
     * @return bool
     */
    public function placeOrder(Order $order)
    {
        $order->setEmail($this->getEmail());
        $order->setTel($this->getTel());
        $order->save();

        return $order->customer()->associate($this->id);
    }
}