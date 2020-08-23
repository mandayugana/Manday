<?php

namespace Manday\Http;

use OutOfBoundsException;
use DomainException;
use Manday\Http\MessageInterface;
use Manday\Http\HeaderInterface;
use Manday\Http\Version;
use Manday\Http\Headers;

abstract class AbstractMessage implements MessageInterface
{
    protected $version = Version::V1_1;

    protected $headers;

    protected $body = '';

    public function __construct()
    {
        $this->headers = new Headers();
    }

    public function setVersion(string $version): void
    {
        if (in_array($version, [Version::V1_0, Version::V1_1, Version::V2]) === false) {
            throw new DomainException(
                sprintf('HTTP version "%s" is not valid', $version)
            );
        }
        $this->version = $version;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function addHeader(HeaderInterface $header): void
    {
        $this->getHeaders()->add($header);
    }

    public function getHeader(string $name): HeaderInterface
    {
        $headers = $this->getHeaders();
        if ($headers->get($name, null) === null) {
            throw new OutOfBoundsException(
                sprintf('Header "%s" is not found', $name)
            );
        }
        return $headers->get($name);
    }

    public function hasHeader(string $name): bool
    {
        return $this->getHeaders()->has($name);
    }

    public function getHeaders(): Headers
    {
        return $this->headers;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function toString(): string
    {
        return (string) $this->getHeaders() . "\r\n\r\n" . $this->getBody();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}