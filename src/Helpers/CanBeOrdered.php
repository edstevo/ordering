<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Helpers;

use EdStevo\Ordering\Models\Order;
use EdStevo\Ordering\Models\OrderItem;

trait CanBeOrdered
{

    /**
     * Return the data necessary in order to be included in the order
     *
     * @return array
     */
    public function getOrderData() : array
    {
        return [
            'order_item_id'     => $this->getId(),
            'order_item_type'   => get_class($this),
            'name'              => $this->getName(),
            'description'       => $this->getDescription(),
            'amount'            => $this->getPrice()
        ];
    }

    /**
     * Expressive way to add this product to an order
     *
     * @param \EdStevo\Ordering\Models\Order $order
     *
     * @return bool
     */
    public function addToOrder(Order $order) : bool
    {
        return $order->addItem($this);
    }

    /**
     * Define relationship to order item
     *
     * @return mixed
     */
    public function orderItem()
    {
        return $this->morphMany(OrderItem::class, 'item');
    }
}