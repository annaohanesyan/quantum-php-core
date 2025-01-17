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

namespace Quantum\Libraries\Database\Idiorm\Statements;

use Quantum\Libraries\Database\DbalInterface;

/**
 * Trait Model
 * @package Quantum\Libraries\Database\Idiorm\Statements
 */
trait Model
{

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function create(): DbalInterface
    {
        $this->getOrmModel()->create();
        return $this;
    }

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function prop(string $key, $value = null)
    {
        if (!is_null($value)) {
            $this->getOrmModel()->$key = $value;
        } else {
            return $this->getOrmModel()->$key ?? null;
        }
    }

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function save(): bool
    {
        return $this->getOrmModel()->save();
    }

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function delete(): bool
    {
        return $this->getOrmModel()->delete();
    }

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function deleteMany(): bool
    {
        return $this->getOrmModel()->delete_many();
    }

}