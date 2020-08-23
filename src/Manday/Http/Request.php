<?php

namespace Manday\Http;

use DomainException;
use OutOfBoundsException;
use Manday\Http\AbstractMessage;
use Manday\Http\RequestInterface;
use Manday\Http\RequestMethod;
use Manday\Uri\Uri;
use Manday\Uri\UriInterface;

class Request extends AbstractMessage implements RequestInterface
{
    protected $method = RequestMethod::GET;

    protected $url;

    protected $query = [];

    public function setUrl($url): void
    {
        if (is_string($url)) {
            $url = new Uri($url);
        }

        if (($url instanceof UriInterface) === false) {
            throw new DomainException('Invalid URL');
        }
        
        $this->url = $url;

        // add host header
        $this->addHeader(new Header('Host', $this->getUrl()->getHost()));
    }

    public function getUrl(): UriInterface
    {
        return $this->url;
    }

    public function setMethod(string $method): void
    {
        $requestMethods = [
            RequestMethod::OPTIONS,
            RequestMethod::GET,
            RequestMethod::HEAD,
            RequestMethod::POST,
            RequestMethod::PUT,
            RequestMethod::DELETE,
            RequestMethod::TRACE,
            RequestMethod::CONNECT,
            RequestMethod::PATCH,
            RequestMethod::PROPFIND,
        ];
        if (in_array($method, $requestMethods) === false) {
            throw new DomainException(
                sprintf('HTTP method "%s" is not valid', $method)
            );
        }
        $this->method = $method;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setQuery(string $name, $value): void
    {
        $this->query[$name] = $value;
    }

    public function getQuery(string $name, $default)
    {
        return $this->query[$name] ?? $default;
    }

    public function toString(): string
    {
        $statusLine = sprintf(
            '%s %s HTTP/%s',
            $this->getMethod(),
            $this->getUrl()->getPath(),
            $this->getVersion()
        );
     
        // place Host header at first position
        try {
            $hostHeader = $this->getHeader('Host');
        } catch (OutOfBoundsException $e) {
            // no Host header found
            // get host from URL
            $hostHeader = new Header('Host', $this->getUrl()->getHost());
            $this->addHeader($hostHeader);
        } finally {
            // $headers = ['host' => $hostHeader] + $this->getHeaders()->toArray();
            // var_dump($headers);
        }
        
        return $statusLine . "\r\n" . parent::toString();
    }
}