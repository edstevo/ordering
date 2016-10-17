<?php
/**
 *  Copyright (c) 2016.
 *  This was created by Ed Stephenson (edward@flowflex.com).
 *  You must get permission to use this work.
 */

namespace EdStevo\Ordering\Contracts;

interface CanBeOrdered
{

    /**
     * Get the id of the order item
     *
     * @return mixed
     */
    public function getId();

    /**
     * Get the name of the order item
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Get the price of the order item
     *
     * @return float
     */
    public function getPrice() : float;

    /**
     * Get the description of the order item
     *
     * @return string
     */
    public function getDescription() : string;

    /**
     * Return the data necessary in order to be included in the order
     *
     * @return array
     */
    public function getOrderData() : array;
}