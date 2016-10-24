<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Tests\Models;

use EdStevo\Ordering\Tests\OrderingTestCase;

class CanBeOrderedModelTest extends OrderingTestCase
{

    public function testGetId()
    {
        $testProduct    = factory(config('ordering.product_model'))->create();

        $this->assertTrue(method_exists($testProduct, 'getId'), "You must define a getId method on your product model");

        $id             = $testProduct->getId();
        $this->assertTrue(is_int($id), "The getId method on your product model must return an integer");
    }

    public function testGetName()
    {
        $testProduct    = factory(config('ordering.product_model'))->create();

        $this->assertTrue(method_exists($testProduct, 'getName'), "You must define a getName method on your product model");

        $name           = $testProduct->getName();
        $this->assertTrue(is_string($name), "The getName method on your product model must return an string");
    }

    public function testGetPrice()
    {
        $testProduct    = factory(config('ordering.product_model'))->create();

        $this->assertTrue(method_exists($testProduct, 'getPrice'), "You must define a getPrice method on your product model");

        $price          = $testProduct->getPrice();
        $this->assertTrue(is_numeric($price), "The getPrice method on your product model must return a numeric value");
    }

    public function testGetDescription()
    {
        $testProduct    = factory(config('ordering.product_model'))->create();

        $this->assertTrue(method_exists($testProduct, 'getDescription'), "You must define a getDescription method on your product model");

        $value          = $testProduct->getDescription();
        $this->assertTrue(is_string($value), "The getDescription method on your product model must return a string");
    }

    public function testGetOrderData()
    {
        $testProduct    = factory(config('ordering.product_model'))->create();

        $this->assertTrue(method_exists($testProduct, 'getOrderData'), "You must define a getOrderData method on your product model");

        $value          = $testProduct->getOrderData();
        $this->assertTrue(is_array($value), "The getOrderData method on your product model must return an array");

        $this->assertArrayHasKey('order_item_id', $value, "Your method for GetOrderData does not return an id");
        $this->assertArrayHasKey('order_item_type', $value, "Your method for GetOrderData does not return a name");
        $this->assertArrayHasKey('description', $value, "Your method for GetOrderData does not return a description");
        $this->assertArrayHasKey('amount', $value, "Your method for GetOrderData does not return an amount");
        $this->assertArrayHasKey('name', $value, "Your method for GetOrderData does not return a type");
    }
}
