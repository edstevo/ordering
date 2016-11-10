<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Tests\Models;

use Carbon\Carbon;
use EdStevo\Billing\Models\PaymentCard;
use EdStevo\Ordering\Mail\OrderConfirmed;
use EdStevo\Ordering\Models\Order;
use EdStevo\Ordering\Models\OrderItem;
use EdStevo\Ordering\Tests\OrderingTestCase;
use Illuminate\Support\Facades\DB;
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
        $this->mock('EdStevo\Billing\Contracts\Charge')->shouldReceive('chargeUnknown')->once()->andReturn([
            'id'        => "ch_19DcR6J39UKp6rErgBDbMYxr",
            'created'   => Carbon::now(),
            'paid'      => true,
            'currency'  => 'GBP'
        ]);

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

        $this->assertNotNull($testOrder->charge_id);
        $this->assertNotNull($testOrder->charged_at);
        $this->assertTrue($testOrder->paid);
    }

    public function testChargeKnownCustomerDefaultCard()
    {
        $testCustomer   = factory(config('ordering.customer_model'))->create();
        $this->be($testCustomer);

        $this->mock('EdStevo\Billing\Contracts\Charge')->shouldReceive('chargeCustomer')->once()->andReturn([
            'id'        => "ch_19DcR6J39UKp6rErgBDbMYxr",
            'created'   => Carbon::now(),
            'paid'      => true,
            'currency'  => 'GBP'
        ]);

        $testOrder      = $testCustomer->placeOrder();

        $testProduct1   = factory(config('ordering.product_model'))->create();
        $testProduct2   = factory(config('ordering.product_model'))->create();
        $testProduct3   = factory(config('ordering.product_model'))->create();

        $orderItem1     = $testOrder->addItem($testProduct1);
        $orderItem2     = $testOrder->addItem($testProduct2);
        $orderItem3     = $testOrder->addItem($testProduct3);

        $testOrder->pay();

        $this->assertNotNull($testOrder->charge_id);
        $this->assertNotNull($testOrder->charged_at);
        $this->assertTrue($testOrder->paid);
    }

    public function testChargeKnownCustomerOtherCard()
    {
        $testCustomer   = factory(config('ordering.customer_model'))->create();
        $this->be($testCustomer);

        $testCard       = PaymentCard::create([
            'customer_id'   => $testCustomer->getId(),
            'payment_id'    => 'yada'
        ]);

        $this->mock('EdStevo\Billing\Contracts\Charge')->shouldReceive('chargeCustomer')->once()->andReturn([
            'id'        => "ch_19DcR6J39UKp6rErgBDbMYxr",
            'created'   => Carbon::now(),
            'paid'      => true,
            'currency'  => 'GBP'
        ]);

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

    public function testSetName()
    {
        $order          = Order::create();

        $firstname      = 'Joe';
        $lastname       = 'Bloggs';

        $this->seeInDatabase('orders', ['id' => $order->id, 'firstname' => null, 'lastname' => null]);

        $order->setName($firstname, $lastname);

        $this->seeInDatabase('orders', ['id' => $order->id, 'firstname' => $firstname, 'lastname' => $lastname]);
    }

    public function testSetEmail()
    {
        $order      = Order::create();

        $email      = 'joe.bloggs@fake.com';

        $this->seeInDatabase('orders', ['id' => $order->id, 'email' => null]);

        $order->setEmail($email);

        $this->seeInDatabase('orders', ['id' => $order->id, 'email' => $email]);
    }

    public function testSetTel()
    {
        $order      = Order::create();

        $tel        = '01234567890';

        $this->seeInDatabase('orders', ['id' => $order->id, 'tel' => null]);

        $order->setTel($tel);

        $this->seeInDatabase('orders', ['id' => $order->id, 'tel' => $tel]);
    }

    public function testSetDeliveryAddressUnknownAddress()
    {
        $address        = $this->address->generate([], false);
        $addressData    = $address->toArray();
        $order          = Order::create();

        $this->seeInDatabase('orders', ['id' => $order->id, 'delivery_address_id' => null]);
        $this->notSeeInDatabase('addresses', $addressData);

        $order->setDeliveryAddress($addressData['address_1'], $addressData['address_2'], $addressData['address_3'], $address['city'], $address['post_code'], $address['country_id']);

        $this->notSeeInDatabase('orders', ['id' => $order->id, 'delivery_address_id' => null]);
        $this->seeInDatabase('addresses', $addressData);
    }

    public function testSetDeliveryAddressKnownAddress()
    {
        $address        = $this->address->generate();
        $addressData    = $address->toArray();
        $order          = Order::create();

        $this->seeInDatabase('orders', ['id' => $order->id, 'delivery_address_id' => null]);
        $this->seeInDatabase('addresses', $addressData);

        $order->setDeliveryAddress($addressData['address_1'], $addressData['address_2'], $addressData['address_3'], $address['city'], $address['post_code'], $address['country_id']);

        $this->seeInDatabase('orders', ['id' => $order->id, 'delivery_address_id' => $address->id]);
        $this->seeInDatabase('addresses', $addressData);
    }
}
