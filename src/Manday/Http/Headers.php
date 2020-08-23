<?php

namespace Manday\Http;

use Countable;
use IteratorAggregate;
use Traversable;
use Manday\Http\HeaderInterface;

class Headers implements Countable, IteratorAggregate
{
    protected $headers = [];

    public function add(HeaderInterface $header): void
    {
        $lowerName = strtolower($header->getName());
        $this->headers[$lowerName] = $header;
    }

    public function get(string $name, $defaultValue = null)
    {
        $lowerName = strtolower($name);
        if (array_key_exists($lowerName, $this->headers)) {
            return $this->headers[$lowerName];
        } else {
            return $defaultValue;
        }
    }

    public function has(string $name): bool
    {
        $lowerName = strtolower($name);
        return array_key_exists($lowerName, $this->headers);
    }

    public function count(): int
    {
        return count($this->headers);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->headers as $header) {
            yield $header;
        }
    }

    public function toArray(): array
    {
        return $this->headers;
    }

    public function toString(): string
    {
        return implode("\r\n", $this->headers);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}