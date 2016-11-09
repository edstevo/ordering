<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Tests;

use Mockery;

class OrderingTestCase extends \TestCase
{
    protected $address;

    public function setUp()
    {
        parent::setUp();

        $this->address  = app()->make('App\Dao\Address');
    }

    public function mock($class)
    {
        $mock = Mockery::mock($class);

        $this->app->instance($class, $mock);

        return $mock;
    }
}