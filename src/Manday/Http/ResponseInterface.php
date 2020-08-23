<?php

namespace Manday\Http;

use Manday\Http\MessageInterface;

interface ResponseInterface extends MessageInterface
{
    public static function fromString(string $responseString): ResponseInterface;

    public function getStatusCode(): int;

    public function setStatusCode(int $statusCode): void;

    public function setReasonPhrase(string $phrase): void;

    public function getReasonPhrase(): string;

    public function isSuccess(): bool;

    public function isRedirect(): bool;

    public function isClientError(): bool;

    public function isServerError(): bool;
}
