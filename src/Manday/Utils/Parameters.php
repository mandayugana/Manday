<?php

namespace Manday\Utils;

use ArrayObject;
use Traversable;
use Manday\Utils\ParametersInterface;

class Parameters extends ArrayObject implements ParametersInterface
{
    /**
     * Object constructor.
     * 
     * @param array $parameters An array containing inital parameters.
     */
    public function __construct(array $parameters = [])
    {
        $this->parseFromArray($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function parseFromArray(array $parameters): void
    {
        $this->exchangeArray($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function parseFromString(string $parameters): void
    {
        parse_str($parameters, $parsedParameters);
        $this->parseFromArray($parsedParameters);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name, $defaultValue = null)
    {
        if ($this->offsetExists($name)) {
            return $this[$name];
        } else {
            return $defaultValue;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $name, $value): void
    {
        $this[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->exchangeArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return (array) $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return http_build_query($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}