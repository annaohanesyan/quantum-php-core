<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.0.0
 */

namespace Quantum\Libraries\Hasher;

/**
 * Hasher class
 * @package Quantum\Libraries\Hasher
 */
class Hasher
{

    /**
     * The algorithm
     * @var int 
     */
    private $algorithm = PASSWORD_BCRYPT;

    /**
     * The cost
     * @var int 
     */
    private $cost = 12;

    /**
     * Sets the algorithm
     * @param int $algorithm
     * @return $this
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * Gets the current algorithm 
     * @return int
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Sets the cost
     * @param int $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * Gets the current cost
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Hashes the given string
     * @param string $password
     * @return string
     */
    public function hash($password)
    {
        return password_hash($password, $this->algorithm, ['cost' => $this->cost]);
    }

    /**
     * Checks if re-hash needed
     * @param string $hash
     * @return bool
     */
    public function needsRehash($hash)
    {
        return password_needs_rehash($hash, $this->algorithm);
    }

    /**
     * Checks the given string against the hash
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function check($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Gets an info of given hash
     * @param string $hash
     * @return array
     */
    public function info($hash)
    {
        return password_get_info($hash);
    }

}
