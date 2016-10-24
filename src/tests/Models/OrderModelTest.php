<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Tests\Models;

use EdStevo\Billing\Models\PaymentCard;
use EdStevo\Ordering\Mail\OrderConfirmed;
use EdStevo\Ordering\Models\Order;
use EdStevo\Ordering\Models\OrderItem;
use EdStevo\Ordering\Tests\OrderingTestCase;
use Illuminate\Support\Facades\Mail;

class OrderModelTest extends OrderingTestCase 
{

    public function testAddProductToOrder()
    {
        $testOrder      = new Order;
        $testOrder->save();

        $testProduct    = factory(config('ordering.product_model'))->create();

        $result         = $testOrder->addItem($testProduct);
        $this->assertTrue($result instanceof OrderItem);
    }

    public function testGetOrderTotal()
    {
        $testOrder      = new Order;
        $testOrder->save();

        $testProduct1   = factory(config('ordering.product_model'))->create();
        $testProduct2   = factory(config('ordering.product_model'))->create();
        $testProduct3   = factory(config('ordering.product_model'))->create();

        $orderItem1     = $testOrder->addItem($testProduct1);
        $orderItem2     = $testOrder->addItem($testProduct2);
        $orderItem3     = $testOrder->addItem($testProduct3);

        $knownTotal     = 0;
        $knownTotal     += $testProduct1->getPrice();
        $knownTotal     += $testProduct2->getPrice();
        $knownTotal     += $testProduct3->getPrice();

        $this->assertEquals($knownTotal, $testOrder->getTotal());
    }

    public function testChargeUnknownCustomer()
    {
        $this->mock('EdStevo\Billing\Contracts\Charge')->shouldReceive('chargeUnknown')->once()->andReturn(true);

        $testOrder      = new Order;
        $testOrder->save();

        $testProduct1   = factory(config('ordering.product_model'))->create();
        $testProduct2   = factory(config('ordering.product_model'))->create();
        $testProduct3   = factory(config('ordering.product_model'))->create();

        $orderItem1     = $testOrder->addItem($testProduct1);
        $orderItem2     = $testOrder->addItem($testProduct2);
        $orderItem3     = $testOrder->addItem($testProduct3);

        $source         = "TOKEN_ID";
        $testOrder->pay($source);
    }

    public function testChargeKnownCustomerDefaultCard()
    {
        $testCustomer   = factory(config('ordering.customer_model'))->create();
        $this->be($testCustomer);

        $this->mock('EdStevo\Billing\Contracts\Charge')->shouldReceive('chargeCustomer')->once()->andReturn(true);

        $testOrder      = $testCustomer->placeOrder();

        $testProduct1   = factory(config('ordering.product_model'))->create();
        $testProduct2   = factory(config('ordering.product_model'))->create();
        $testProduct3   = factory(config('ordering.product_model'))->create();

        $orderItem1     = $testOrder->addItem($testProduct1);
        $orderItem2     = $testOrder->addItem($testProduct2);
        $orderItem3     = $testOrder->addItem($testProduct3);

        $testOrder->pay();
    }

    public function testChargeKnownCustomerOtherCard()
    {
        $testCustomer   = factory(config('ordering.customer_model'))->create();
        $this->be($testCustomer);

        $testCard       = PaymentCard::create([
            'customer_id'   => $testCustomer->getId(),
            'payment_id'    => 'yada'
        ]);

        $this->mock('EdStevo\Billing\Contracts\Charge')->shouldReceive('chargeCustomer')->once()->andReturn(true);

        $testOrder      = $testCustomer->placeOrder();

        $testProduct1   = factory(config('ordering.product_model'))->create();
        $testProduct2   = factory(config('ordering.product_model'))->create();
        $testProduct3   = factory(config('ordering.product_model'))->create();

        $orderItem1     = $testOrder->addItem($testProduct1);
        $orderItem2     = $testOrder->addItem($testProduct2);
        $orderItem3     = $testOrder->addItem($testProduct3);

        $testOrder->pay($testCard);
    }

    public function testSendConfirmation()
    {
        Mail::fake();

        $testCustomer   = factory(config('ordering.customer_model'))->create();
        $this->be($testCustomer);

        $testOrder      = $testCustomer->placeOrder();

        $testProduct1   = factory(config('ordering.product_model'))->create();
        $testProduct2   = factory(config('ordering.product_model'))->create();
        $testProduct3   = factory(config('ordering.product_model'))->create();

        $orderItem1     = $testOrder->addItem($testProduct1);
        $orderItem2     = $testOrder->addItem($testProduct2);
        $orderItem3     = $testOrder->addItem($testProduct3);

        $testOrder->sendConfirmation();

        Mail::assertSent(OrderConfirmed::class, function ($mail) use ($testOrder) {
            return $mail->order->id === $testOrder->id;
        });

        Mail::assertSentTo([$testOrder->getEmail()], OrderConfirmed::class);
    }

    public function testSendConfirmationActuallySend()
    {
        $testCustomer   = factory(config('ordering.customer_model'))->create();
        $this->be($testCustomer);

        $testOrder      = $testCustomer->placeOrder();

        $testProduct1   = factory(config('ordering.product_model'))->create();
        $testProduct2   = factory(config('ordering.product_model'))->create();
        $testProduct3   = factory(config('ordering.product_model'))->create();

        $orderItem1     = $testOrder->addItem($testProduct1);
        $orderItem2     = $testOrder->addItem($testProduct2);
        $orderItem3     = $testOrder->addItem($testProduct3);

        $testOrder->sendConfirmation();
    }
}
