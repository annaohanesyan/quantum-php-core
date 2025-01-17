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
 * @since 2.5.0
 */

namespace Quantum\Exceptions;

/**
 * Class StopExecutionException
 * @package Quantum\Exceptions
 */
class StopExecutionException extends \Exception
{

    /**
     * Script execution terminated message
     */
    const EXECUTION_TERMINATED = 'Execution was terminated';

    /**
     * @return \Quantum\Exceptions\StopExecutionException
     */
    public static function executionTerminated(): StopExecutionException
    {
        return new static(self::EXECUTION_TERMINATED);
    }
}
