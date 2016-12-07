<?php

namespace EdStevo\Ordering\Mail;

use EdStevo\Ordering\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

class OrderConfirmed extends Mailable
{
    use SerializesModels;
    public $order;

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

            $this->view(config('ordering.views.order_confirmation'));

        } else {

            $this->view('ordering::emails.orders.order_confirmation');

        }

        return $this->with('order', $this->order)
                    ->with('items', $this->order->items)
                    ->subject('Your Order #' . $this->order->id)
                    ->bcc('sales@flowflex.com');
    }
}
