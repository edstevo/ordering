<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Models;

use EdStevo\Ordering\Contracts\CanBeOrdered;
use EdStevo\Ordering\Contracts\OrderContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model implements OrderContract
{

    /**
     * Define the relationship for the items on this order
     *
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Define the relationship to the customer that placed the order
     *
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->hasMany(config('ordering.customer_model'));
    }

    /**
     * Set the email for this order
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email    = $email;
    }

    /**
     * Set the tel for this order
     *
     * @param string $tel
     *
     * @return void
     */
    public function setTel(string $tel)
    {
        $this->tel    = $tel;
    }

    /**
     * Add an order item to this order
     *
     * @param \EdStevo\Ordering\Contracts\CanBeOrdered $item
     *
     * @return OrderItem
     */
    public function addItem(CanBeOrdered $item) : OrderItem
    {
        $orderItemData      = $item->getOrderData();
        return $this->items()->create($orderItemData);
    }

    /**
     * Charge this order
     *
     * @return bool
     */
    public function charge() : bool
    {
        // TODO: Implement charge() method.
    }

    /**
     * Get the total cost of this order
     *
     * @return float
     */
    public function getTotal() : float
    {
        return $this->items->pluck('amount')->sum();
    }

    /**
     * Notify the customer that their order has been dispatched
     *
     * @return bool
     */
    public function notifyOfDispatch() : bool
    {
        // TODO: Implement notifyOfDispatch() method.
    }

    /**
     * Send an invoice to the customer
     *
     * @return bool
     */
    public function sendInvoice() : bool
    {
        // TODO: Implement sendInvoice() method.
    }
}