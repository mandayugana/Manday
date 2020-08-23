<?php

namespace Manday\Http;

use Manday\Http\Client\Adapter\AdapterInterface;
use Manday\Http\ClientInterface;
use Manday\Http\Header;
use Manday\Http\Request;
use Manday\Http\RequestInterface;
use Manday\Http\RequestMethod;
use Manday\Http\Response;
use Manday\Http\ResponseInterface;
use Manday\Http\ResponseStatus;

class Client implements ClientInterface
{
    protected $currentRequest;

    protected $userAgent;

    protected $adapter;

    protected $redirectionsCount = 0;

    protected $maxRedirections = 5;

    public function __construct()
    {
        $this->setUserAgent(static::class);
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getUseragent(): string
    {
        return $this->userAgent;
    }

    public function setAdapter(AdapterInterface $adapter): void
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    public function countRedirections(): int
    {
        return $this->redirectionsCount;
    }

    public function setMaxRedirections(int $count): void
    {
        // TODO: validate $count value
        $this->maxRedirections = $count;
    }

    public function getMaxRedirections(): int
    {
        return $this->maxRedirections;
    }
    
    public function send(RequestInterface $request): ResponseInterface
    {
        // reset redirections count
        $this->redirectionsCount = 0;

        // set current request to make other methods able to work with it
        $this->currentRequest = $request;

        do {
            // prepare current request
            $this->prepareRequest($this->currentRequest);

            // send request
            $curl = curl_init((string) $this->currentRequest->getUrl());
            $headers = [];
            foreach ($this->currentRequest->getHeaders() as $header) {
                $headers[] = (string) $header;
            }
            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_HTTPHEADER => $headers,
            ];
            curl_setopt_array($curl, $options);
            $result = curl_exec($curl);
            curl_close($curl);

            // create response
            $response = Response::fromString($result);
        } while ($this->mustDoRedirection($response));

        // clear current request
        $this->currentRequest = null;

        return $response;
    }

    protected function prepareRequest(RequestInterface $request): void
    {
        // set user agent header
        if ($request->hasHeader('User-Agent') === false) {
            if ($this->getUserAgent()) {
                $request->addHeader(new Header('User-Agent', $this->getUserAgent()));
            }
        }
        
    }

    protected function mustDoRedirection(ResponseInterface $response): bool
    {
        if (
            $response->isRedirect()
            && $this->redirectionsCount < $this->getMaxRedirections()
        ) {
            // increase redirections count
            $this->redirectionsCount++;

            // handle response status 303
            if ($response->getStatusCode() == ResponseStatus::CODE_303) {
                // replace current request with new clean GET request
                $request = new Request();
                $request->setMethod(RequestMethod::GET);
                $this->currentRequest = $request;
            }
            // set the new location
            $this->currentRequest->setUrl($response->getHeader('Location')->getValue());
            return true;
        }
        return false;
    }
}