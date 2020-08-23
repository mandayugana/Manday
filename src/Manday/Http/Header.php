<?php

namespace Manday\Http;

use DomainException;
use Manday\Http\HeaderInterface;

class Header implements HeaderInterface
{
    protected $name;

    protected $value;

    public static function fromString(string $headerString): HeaderInterface
    {
        [$name, $value] = explode(":", $headerString, 2);
        return new static($name, trim($value));
    }

    public function __construct(string $name, string $value)
    {
        $this->assertName($name);
        $this->name = $name;
        $this->setValue($value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setValue(string $value): void
    {
        if ($this->isValid($value) === false) {
            throw new DomainException('Invalid header value');
        }

        if (preg_match('/^\s+$/', $value)) {
            // value containing white-space only
            // set as empty value
            $value = '';
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return (string) $this->value;
    }

    public function toString(): string
    {
        return $this->getName() . ': ' . $this->getValue();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    protected function assertName(string $name): void
    {
        if (empty($name)) {
            throw new DomainException('Header name must not be empty');
        }

        if (preg_match('/^[!#$%&\'*+\-\.\^_`|~0-9a-zA-Z]+$/', $name) === false) {
            throw new DomainException(
                'Header name must be a valid RFC 7230 (section 3.2) field-name'
            );
        }
    }

    private function isValid($value): bool
    {
        $value  = (string) $value;
        $length = strlen($value);
        for ($i = 0; $i < $length; $i += 1) {
            $ascii = ord($value[$i]);

            // Non-visible, non-whitespace characters
            // 9 === horizontal tab
            // 32-126, 128-254 === visible
            // 127 === DEL
            // 255 === null byte
            if (($ascii < 32 && $ascii !== 9)
                || $ascii === 127
                || $ascii > 254
            ) {
                return false;
            }
        }

        return true;
    }
}