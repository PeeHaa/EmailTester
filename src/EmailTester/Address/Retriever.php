<?php
/**
 * Interface for services that retrieve emailaddress from websites
 *
 * PHP version 5.4
 *
 * @category   EmailTester
 * @package    Address
 * @author     Pieter Hordijk <info@EmailTester.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace EmailTester\Address;

interface Retriever
{
    /**
     * Retrieves the addresses from a website
     *
     * @return array List of the retrieved emailaddresses
     * @throws \EmailTester\Address\BadResponseException
     */
    public function retrieve();
}
