<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Contracts;

interface OrderItemContract
{

    /**
     *  Relationships
     */

    /**
     * Define the relationship to access the item
     */
    public function item();

}