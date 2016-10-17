<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Contracts;

use EdStevo\Ordering\Models\Order;

interface PlacesOrders
{

    /**
     * Expressive way for a customer to place an order
     *
     * @param \EdStevo\Ordering\Models\Order $order
     *
     * @return bool
     */
    public function placeOrder(Order $order) : bool;

    /**
     * Return the email for this customer
     *
     * @return string
     */
    public function getEmail() : string;

    /**
     * Return the tel for this customer
     *
     * @return string
     */
    public function getTel() : string;
}