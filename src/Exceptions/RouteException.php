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
 * Class RouteException
 * @package Quantum\Exceptions
 */
class RouteException extends \Exception
{
    /**
     * Route not found message
     */
    const ROUTE_NOT_FOUND = 'Route Not Found';

    /**
     * Route is not a closure message
     */
    const ROUTES_NOT_CLOSURE = 'Route is not a closure';

    /**
     * Repetitive route message with same method
     */
    const REPETITIVE_ROUTE_SAME_METHOD = 'Repetitive Routes with same method `{%1}`';

    /**
     * Repetitive route message with in different modules message
     */
    const REPETITIVE_ROUTE_DIFFERENT_MODULES = 'Repetitive Routes in different modules';

    /**
     * Incorrect method message
     */
    const INCORRECT_METHOD = 'Incorrect Method `{%1}`';

    /**
     * Name can not be set before route definition messgae
     */
    const NAME_BEFORE_ROUTE_DEFINITION = 'Names can not be set before route definition';

    /**
     * Nam can not be set on groups message
     */
    const NAME_ON_GROUP = 'Name can not be set on groups message';

    /**
     * Route names should be unique message
     */
    const NAME_IS_NOT_UNIQUE = 'Route names should be unique';

    /**
     * @return \Quantum\Exceptions\RouteException
     */
    public static function notFound(): RouteException
    {
        return new static(self::ROUTE_NOT_FOUND, E_ERROR);
    }

    /**
     * @return \Quantum\Exceptions\RouteException
     */
    public static function notClosure(): RouteException
    {
        return new static(self::ROUTES_NOT_CLOSURE, E_WARNING);
    }

    /**
     * @param string $name
     * @return \Quantum\Exceptions\RouteException
     */
    public static function repetitiveRouteSameMethod(string $name): RouteException
    {
        return new static(_message(self::REPETITIVE_ROUTE_SAME_METHOD, $name), E_WARNING);
    }

    /**
     * @return \Quantum\Exceptions\RouteException
     */
    public static function repetitiveRouteDifferentModules(): RouteException
    {
        return new static(self::REPETITIVE_ROUTE_DIFFERENT_MODULES, E_WARNING);
    }

    /**
     * @param string|null $name
     * @return \Quantum\Exceptions\RouteException
     */
    public static function incorrectMethod(?string $name): RouteException
    {
        return new static(_message(self::INCORRECT_METHOD, $name), E_WARNING);
    }

    /**
     * @return \Quantum\Exceptions\RouteException
     */
    public static function nameBeforeDefinition(): RouteException
    {
        return new static(self::NAME_BEFORE_ROUTE_DEFINITION);
    }

    /**
     * @return \Quantum\Exceptions\RouteException
     */
    public static function nameOnGroup(): RouteException
    {
        return new static(self::NAME_ON_GROUP);
    }

    /**
     * @return \Quantum\Exceptions\RouteException
     */
    public static function nonUniqueName(): RouteException
    {
        return new static(self::NAME_IS_NOT_UNIQUE);
    }
}
