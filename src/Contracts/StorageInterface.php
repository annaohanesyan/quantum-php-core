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
 * @since 2.6.0
 */

namespace Quantum\Contracts;

/**
 * Interface StorageInterface
 * @package Quantum\Contracts
 */
interface StorageInterface
{

    /**
     * Should be implemented in derived classes to get whole storage data
     */
    public function all();

    /**
     * Should be implemented in derived classes to check if storage contains a key
     * @param string $key
     */
    public function has(string $key);

    /**
     * Should be implemented in derived classes to get value from storage by key
     * @param string $key
     */
    public function get(string $key);

    /**
     * Should be implemented in derived classes to set storage value with given key
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value);

    /**
     * Should be implemented in derived classes to delete data with given key from session
     * @param string $key
     */
    public function delete(string $key);

    /**
     * Should be implemented in derived classes to delete whole storage data
     */
    public function flush();
}
