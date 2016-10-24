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
     * Define the relationship between the customer and the orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders();

    /**
     * Expressive way for a customer to place an order
     *
     * @param \EdStevo\Ordering\Models\Order $order
     *
     * @return bool
     */
    public function placeOrder() : Order;

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

    /**
     * Return the id for this customer
     *
     * @return string
     */
    public function getId();
}