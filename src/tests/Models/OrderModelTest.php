<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Tests\Models;

use EdStevo\Ordering\Models\Order;
use EdStevo\Ordering\Models\OrderItem;
use EdStevo\Ordering\Tests\OrderingTestCase;

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
}
