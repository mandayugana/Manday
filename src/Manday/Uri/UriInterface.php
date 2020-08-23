<?php

namespace Manday\Uri;

use Manday\Utils\ParametersInterface;

interface UriInterface
{
    public function parseString(string $uriString): void;

    public function setScheme(string $scheme): void;

    public function getScheme(): string;

    public function getAuthority(): string;

    public function getUserInfo(): string;

    public function setUser(?string $user): void;

    public function getUser(): ?string;

    public function setPassword(?string $password): void;

    public function getPassword(): ?string;

    public function setHost(?string $host): void;

    public function getHost(): ?string;

    public function setPort(?int $port): void;

    public function getPort(): ?int;

    public function setPath(string $path): void;

    public function getPath(): string;

    public function setQuery(ParametersInterface $query): void;

    public function getQuery(): ParametersInterface;

    public function setFragment(?string $fragment): void;

    public function getFragment(): ?string;
    
    public function toString(): string;

    public function __toString(): string;
}