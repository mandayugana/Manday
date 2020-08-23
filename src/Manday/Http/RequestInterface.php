<?php

namespace Manday\Http;

use Manday\Http\ResponseInterface;
use Manday\Http\MessageInterface;
use Manday\Uri\UriInterface;

interface RequestInterface extends MessageInterface
{
    public function setUrl($url): void;

    public function getUrl(): UriInterface;

    public function setMethod(string $method): void;

    public function getMethod(): string;

    public function setQuery(string $name, $value): void;

    public function getQuery(string $name, $default);
}