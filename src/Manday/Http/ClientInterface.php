<?php

namespace Manday\Http;

use Manday\Http\Client\Adapter\AdapterInterface;
use Manday\Http\RequestInterface;
use Manday\Http\ResponseInterface;

interface ClientInterface
{
    public function setUserAgent(string $userAgent): void;

    public function getUseragent(): string;

    public function setAdapter(AdapterInterface $adapter): void;

    public function getAdapter(): AdapterInterface;

    public function setMaxRedirections(int $count): void;

    public function getMaxRedirections(): int;

    public function countRedirections(): int;
    
    public function send(RequestInterface $request): ResponseInterface;
}