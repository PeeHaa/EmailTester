<?php
/**
 * Retrieves the emailaddresses from:
 * http://fightingforalostcause.net/misc/2006/compare-email-regex.php
 *
 * PHP version 5.4
 *
 * @category   EmailTester
 * @author     Pieter Hordijk <info@EmailTester.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace EmailTester;

use EmailTester\Address\FightingForALostCauseRetriever,
    EmailTester\Storage\Address as AddressStorage;

/**
 * Load the project
 */
require_once __DIR__ . '/../bootstrap.php';

/**
 * Retrieve all addresses from fightingforalostcause.net
 */
$fightingForALostCauseRetriever = new FightingForALostCauseRetriever();
$addresses = $fightingForALostCauseRetriever->retrieve();

/**
 * Store the addresses
 */
$addressStorage = new AddressStorage($dbConnection);
$addressStorage->store($addresses);
