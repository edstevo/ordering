<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Models;

use EdStevo\Ordering\Mail\OrderConfirmed;
use EdStevo\Ordering\Contracts\CanBeOrdered;
use EdStevo\Ordering\Contracts\OrderContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Order extends Model implements OrderContract
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'tel'
    ];

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
        return $this->belongsTo(config('ordering.customer_model'));
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
     * Get the email for this order
     *
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
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
     * Pay for the order
     *
     * @return bool
     */
    public function pay($source = null) : bool
    {
        if(Auth::check())
        {
            return $this->chargeRepository()->chargeCustomer($this->customer, $this->getTotal(), $source);
        }

        return $this->chargeRepository()->chargeUnknown($source, $this->getTotal());
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
     * Send as confirmation email to the customer
     *
     * @return void
     */
    public function sendConfirmation()
    {

        Mail::to($this->getEmail())->send(new OrderConfirmed($this));
    }

    /**
     *  Return the billing charge repository
     *
     * return \EdStevo\Billing\Contracts\Charge;
     */
    private function chargeRepository()
    {
        return app()->make('EdStevo\Billing\Contracts\Charge');
    }
}