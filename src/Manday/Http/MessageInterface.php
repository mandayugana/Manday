<?php

namespace Manday\Http;

use Manday\Http\HeaderInterface;
use manday\Http\Headers;

interface MessageInterface
{
    public function setVersion(string $version): void;

    public function getVersion(): string;

    public function addHeader(HeaderInterface $header): void;

    public function getHeader(string $name): HeaderInterface;

    public function hasHeader(string $name): bool;

    public function getHeaders(): Headers;

    public function setBody(string $body): void;

    public function getBody(): string;
    
    public function toString(): string;

    public function __toString(): string;
}
