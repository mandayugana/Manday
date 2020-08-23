<?php

namespace Manday\Http;

interface HeaderInterface
{
    public static function fromString(string $headerString): HeaderInterface;

    public function getName(): string;

    public function setValue(string $value): void;

    public function getValue(): string;
    
    public function toString(): string;

    public function __toString(): string;
}