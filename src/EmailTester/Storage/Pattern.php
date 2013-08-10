<?php
/**
 * Service that stores patterns
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

class Pattern
{
    /**
     * @var \PDO The database connection
     */
    private $dbConnection;

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
     * Gets the id of the pattern
     *
     * @param string $pattern The pattern of which to get the id
     *
     * @return string The id which belongs to the pattern
     */
    public function getId($pattern)
    {
        $stmt = $this->dbConnection->prepare('SELECT id FROM patterns WHERE pattern = :pattern');
        $stmt->execute([
            'pattern' => $pattern,
        ]);

        $result = $stmt->fetch();

        if ($result !== false) {
            return $this->buildWebscaleId($result['id']);
        }

        $this->store($pattern);

        return $this->getId($pattern);
    }

    /**
     * Get a pattern by its webscale id
     *
     * @param string $id The webscale id
     *
     * @return null|string The regex pattern matching the id if it exists
     */
    public function getById($id)
    {
        $stmt = $this->dbConnection->prepare('SELECT pattern FROM patterns WHERE id = :id');
        try {
            $stmt->execute([
                'id' => $this->deWebscaleId($id),
            ]);
        } catch(\PDOException $e) {
            return null;
        }

        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result['pattern'];
    }

    /**
     * Stores the pattern
     *
     * @param string $pattern The pattern to store
     */
    private function store($pattern)
    {
        $stmt = $this->dbConnection->prepare('INSERT INTO patterns (pattern) VALUES (:pattern)');
        $stmt->execute([
            'pattern' => $pattern,
        ]);
    }

    /**
     * Creates a webscale id
     *
     * @param int $id The id to make webscale
     *
     * @return string Webscale id
     */
    private function buildWebscaleId($id)
    {
        return str_replace('=', '', base64_encode($id));
    }

    /**
     * Converts a webscale id to numeric
     *
     * @param string $id The webscale id
     *
     * @return int Numeric id
     */
    private function deWebscaleId($id)
    {
        $encodedId = $id;
        if (strlen($id) % 4 !== 0) {
            $encodedId .= str_repeat('=', (4 - strlen($id) % 4));
        }

        return base64_decode($encodedId);
    }
}
