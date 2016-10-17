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

class OrderItemModelTest extends OrderingTestCase
{

    public function testGetOriginalItem()
    {
        $productModel   = config('ordering.product_model');

        $testOrder      = new Order;
        $testOrder->save();

        $testProduct    = factory($productModel)->create();

        $result         = $testOrder->addItem($testProduct);
        $this->assertTrue($result instanceof OrderItem);

        $product        = $result->getProduct();
        $this->assertTrue($product instanceof $productModel);
    }
}
