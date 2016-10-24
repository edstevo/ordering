<?php

namespace EdStevo\Ordering\Mail;

use EdStevo\Ordering\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

class OrderConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order            = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (View::exists(config('ordering.views.order_confirmation')))
        {
            return $this->view(config('ordering.views.order_confirmation'))
                        ->with('order', $this->order)
                        ->with('items', $this->order->items);
        }

        return $this->view('ordering::emails.orders.order_confirmation')
                    ->with('order', $this->order)
                    ->with('items', $this->order->items);
    }
}
