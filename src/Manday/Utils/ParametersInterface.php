<?php

namespace Manday\Utils;

use ArrayAccess;
use Countable;
use Serializable;
use Traversable;

interface ParametersInterface extends ArrayAccess, Countable, Serializable, Traversable
{
    /**
     * Replaces all parameters with parameters from provided array.
     * 
     * @param array $parameters An array containing all parameters.
     * @return void
     */
    public function parseFromArray(array $parameters): void;

    /**
     * Replaces all parameters with parameters from provided string.
     * 
     * @param string $parameters Parameters in URL-encoded query style.
     * @return void
     */
    public function parseFromString(string $parameters): void;

    /**
     * Gets value of a parameter.
     * 
     * @param string $name Parameter name.
     * @param mixed $defaultValue The value to be returned if requested parameter
     * is not found.
     * @return mixed Value of requested parameter.
     */
    public function get(string $name, $defaultValue);

    /**
     * Sets a parameter.
     * 
     * @param string $name Parameter name.
     * @param mixed $value Value of the parameter.
     * @return void
     */
    public function set(string $name, $value): void;

    /**
     * Deletes all parameters.
     * 
     * @return void
     */
    public function clear(): void;

    /**
     * Converts parameters to array.
     * 
     * @return array An array containing all parameters.
     */
    public function toArray(): array;

    /**
     * Converts parameters to string.
     * 
     * @return string All parameters in URL-encoded query style.
     */
    public function toString(): string;

    /**
     * String representation of current object.
     */
    public function __toString(): string;
}