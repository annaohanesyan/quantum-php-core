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

namespace Quantum\Libraries\Database\Sleekdb\Statements;

use Quantum\Libraries\Database\DbalInterface;
use Quantum\Exceptions\DatabaseException;

/**
 * Trait Criteria
 * @package Quantum\Libraries\Database\Sleekdb\Statements
 */
trait Criteria
{

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function criteria(string $column, string $operator, $value = null): DbalInterface
    {
        if (!in_array($operator, $this->operators)) {
            throw DatabaseException::operatorNotSupported($operator);
        }

        array_push($this->criterias, [$column, $operator, $value]);

        return $this;
    }

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function criterias(...$criterias): DbalInterface
    {
        foreach ($criterias as $criteria) {
            if (is_array($criteria[0])) {
                $this->orCriteria($criteria);
                continue;
            }

            $this->criteria(...$criteria);
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function having(string $column, string $operator, string $value = null): DbalInterface
    {
        if (!in_array($operator, $this->operators)) {
            throw DatabaseException::operatorNotSupported($operator);
        }

        array_push($this->havings, [$column, $operator, $value]);

        return $this;
    }

    /**
     * Adds one or more OR criteria in brackets
     * @param array $orCriterias
     */
    protected function orCriteria(array $orCriterias)
    {
        foreach ($orCriterias as $index => $criteria) {
            array_push($this->criterias, $criteria);
            if ($index != array_key_last($orCriterias)) {
                array_push($this->criterias, 'OR');
            }
        }
    }

}