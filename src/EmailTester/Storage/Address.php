<?php
/**
 * Service that stores emailaddresses
 *
 * PHP version 5.4
 *
 * @category   EmailTester
 * @package    Storage
 * @author     Pieter Hordijk <info@EmailTester.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace EmailTester\Storage;

class Address
{
    /**
     * @var \PDO The database connection
     */
    private $dbConnection;

    /**
     * @var array List of addresses to be updated
     */
    private $updatedAddresses = [
        'valid'   => [],
        'invalid' => [],
    ];

    /**
     * Creates instance
     *
     * @param \PDO $dbConnection The database connection
     */
    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Stores the addresses in the database
     *
     * @param array $addresses List of addresses to store
     */
    public function store(array $addresses)
    {
        $stmt = $this->dbConnection->prepare('INSERT INTO addresses (address, "valid") VALUES (:address, :valid)');

        foreach ($addresses['valid'] as $address) {
            $this->storeAddress($stmt, $address, true);
        }

        foreach ($addresses['invalid'] as $address) {
            $this->storeAddress($stmt, $address, false);
        }

        $this->update();
    }

    /**
     * Stores an emailaddress
     *
     * Postgres has no easy upsert solution (well it kinda has but I'm lazy) so dragons be here
     *
     * @param \PDOStatement $stmt    The prepared statement
     * @param string        $address The emailaddress
     * @param boolean       $valid   Whether the emailaddress is valid
     */
    private function storeAddress(\PDOStatement $stmt, $address, $valid)
    {
        try {
            $stmt->execute([
                'address' => $address,
                'valid'   => $valid ? 't' : 'f',
            ]);
        } catch(\PDOException $e) {
            if ($e->getCode() === '23505') {
                if ($valid === true) {
                    $this->updatedAddresses['valid'][] = $address;
                } else {
                    $this->updatedAddresses['invalid'][] = $address;
                }
            }
        }
    }

    /**
     * Updates emailaddresses
     */
    private function update()
    {
        $stmt = $this->dbConnection->prepare('UPDATE addresses SET "valid" = :valid WHERE address = :address');

        foreach ($this->updatedAddresses['valid'] as $address) {
            $this->updateAddress($stmt, $address, 't');
        }

        foreach ($this->updatedAddresses['invalid'] as $address) {
            $this->updateAddress($stmt, $address, 'f');
        }
    }

    /**
     * Updates an emailaddress
     *
     * @param \PDOStatement $stmt    The prepared statement
     * @param string        $address The emailaddress
     * @param boolean       $valid   Whether the emailaddress is valid
     */
    private function updateAddress(\PDOStatement $stmt, $address, $valid)
    {
        $stmt->execute([
            'address' => $address,
            'valid'   => $valid ? 't' : 'f',
        ]);
    }

    /**
     * Gets all the valid addresses in the database
     *
     * @return array List of all the valid emailaddresses
     */
    public function getValid()
    {
        return $this->get(true);
    }

    /**
     * Gets all the invalid addresses in the database
     *
     * @return array List of all the invalid emailaddresses
     */
    public function getInvalid()
    {
        return $this->get(false);
    }

    /**
     * Gets all the emailaddresses based on the valid flag
     *
     * @param boolean $valid What type of addresses to fetch
     *
     * @return array List of all the emailaddresses based on the valid flag
     */
    private function get($valid)
    {
        $stmt = $this->dbConnection->prepare('SELECT address FROM addresses WHERE "valid" = :valid');
        $stmt->execute([
            'valid' => $valid ? 't' : 'f',
        ]);

        $addresses = [];
        foreach ($stmt->fetchAll() as $address) {
            $addresses[] = $address['address'];
        }

        return $addresses;
    }
}
