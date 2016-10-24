<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Contracts;

use EdStevo\Ordering\Models\OrderItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface OrderContract
{

    /**
     *  Relationships
     */

    /**
     * Define the relationship for the items on this order
     *
     * @return HasMany
     */
    public function items();

    /**
     * Define the relationship to the customer that placed the order
     *
     * @return BelongsTo
     */
    public function customer();

    /**
     *  Functions
     */

    /**
     * Add an order item to this order
     *
     * @param \EdStevo\Ordering\Contracts\CanBeOrdered $item
     *
     * @return OrderItem
     */
    public function addItem(CanBeOrdered $item) : OrderItem;

    /**
     * Pay for the order
     *
     * @return bool
     */
    public function pay($source = null) : bool;

    /**
     * Get the total cost of this order
     *
     * @return float
     */
    public function getTotal() : float;

    /**
     * Notify the customer that their order has been dispatched
     *
     * @return bool
     */
    public function notifyOfDispatch() : bool;

    /**
     * Send a confirmation email to the customer
     *
     * @return void
     */
    public function sendConfirmation();
}