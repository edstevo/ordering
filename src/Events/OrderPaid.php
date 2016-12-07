<?php

namespace EdStevo\Ordering\Events;

use EdStevo\Ordering\Models\Order;

class OrderPaid
{
    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order            = $order;
    }
}
