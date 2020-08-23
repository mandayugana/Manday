<?php

namespace Manday\Http;

use DomainException;
use UnexpectedValueException;
use Manday\Http\AbstractMessage;
use Manday\Http\ResponseInterface;
use Manday\Http\ResponseStatus;

class Response extends AbstractMessage implements ResponseInterface
{
    protected $statusCode = ResponseStatus::CODE_200;

    protected $reasonPhrase;

    public static function fromString(string $responseString): ResponseInterface
    {
        [$headerSection, $body] = explode("\r\n\r\n", $responseString, 2);
        $response = new static();

        // parse status
        [$statusLine, $headersString] = explode("\r\n", $headerSection, 2);
        $regex   = '/^HTTP\/(?P<version>1\.[01]|2) (?P<status>\d{3})(?:[ ]+(?P<reason>.*))?$/';
        $matches = [];
        if (preg_match($regex, $statusLine, $matches) === false) {
            throw new UnexpectedValueException(
                'A valid response status line was not found in the provided string'
            );
        }
        $response->setVersion($matches['version']);
        $response->setStatusCode($matches['status']);
        $response->setReasonPhrase($matches['reason'] ?? '');
        
        // set headers
        foreach (explode("\r\n", $headersString) as $headerString) {
            $response->addHeader(Header::fromString($headerString));
        }
        // set body
        $response->setBody($body);

        return $response;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        if (
            $statusCode < ResponseStatus::MIN_STATUS_CODE
            || $statusCode > ResponseStatus::MAX_STATUS_CODE
        ) {
            throw new DomainException(
                sprintf('HTTP status code "%s" is not valid', $statusCode)
            );
        }
        $this->statusCode = $statusCode;
        // refresh reason phrase
        $this->setReasonPhrase(ResponseStatus::getPhrase($this->getStatusCode()));
    }

    public function setReasonPhrase(string $phrase): void
    {
        $this->reasonPhrase = $phrase;
    }

    public function getReasonPhrase(): string
    {
        if (empty($this->reasonPhrase)) {
            $this->setReasonPhrase(ResponseStatus::getPhrase($this->getStatusCode()));
        }
        return (string) $this->reasonPhrase;
    }

    public function isSuccess(): bool
    {
        return $this->getStatusCode() >= ResponseStatus::CODE_200
            && $this->getStatusCode() < ResponseStatus::CODE_300;
    }

    public function isRedirect(): bool
    {
        return $this->getStatusCode() >= ResponseStatus::CODE_300
            && $this->getStatusCode() < ResponseStatus::CODE_400;
    }

    public function isClientError(): bool
    {
        return $this->getStatusCode() >= ResponseStatus::CODE_400
            && $this->getStatusCode() < ResponseStatus::CODE_500;
    }

    public function isServerError(): bool
    {
        return $this->getStatusCode() >= ResponseStatus::CODE_500
            && $this->getStatusCode() <= ResponseStatus::CODE_599;
    }

    public function toString(): string
    {
        $statusLine = sprintf(
            'HTTP/%s %d %s',
            $this->getVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        );
        return $statusLine . "\r\n" . parent::toString();
    }
}