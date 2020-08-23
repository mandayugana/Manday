<?php

namespace Manday\Uri;

use Manday\Uri\UriInterface;
use Manday\Utils\Parameters;
use Manday\Utils\ParametersInterface;

class Uri implements UriInterface
{
    protected $scheme;

    protected $user;

    protected $password;

    protected $host;

    protected $port;

    protected $path;

    protected $query;

    protected $fragment;

    public function __construct(string $uriString = null)
    {
        if ($uriString) {
            $this->parseString($uriString);
        }
    }

    public function parseString(string $uriString): void
    {
        // TODO: validation
        
        $parsedUri = parse_url($uriString);
        // set scheme
        $this->setScheme($parsedUri['scheme']);
        // set user
        if (isset($parsedUri['user'])) {
            $this->setUser($parsedUri['user']);
            // set password
            if (isset($parsedUri['pass'])) {
                $this->setPassword($parsedUri['pass']);
            }
        }
        // set host
        if (isset($parsedUri['host'])) {
            $this->setHost($parsedUri['host']);
        }
        // set port
        if (isset($parsedUri['port'])) {
            $this->setPort($parsedUri['port']);
        }
        // set path
        if (isset($parsedUri['path'])) {
            $this->setPath($parsedUri['path']);
        }
        // set query
        if (isset($parsedUri['query'])) {
            $query = new Parameters();
            $query->parseFromString($parsedUri['query']);
            $this->setQuery($query);
        }
        // set fragment
        if (isset($parsedUri['fragment'])) {
            $this->setFragment($parsedUri['fragment']);
        }
    }

    public function setScheme(string $scheme): void
    {
        $this->scheme = $scheme;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $userInfo = (string) $this->getUserInfo();
        $host = (string) $this->getHost();
        $port = (string) $this->getPort();

        $authority = '';
        if ($host) {
            $authority .= $userInfo ? $userInfo . '@' : '';
            $authority .= $host;
            $authority .= $port ? ':' . $port : '';
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        $user = (string) $this->getUser();
        $password = (string) $this->getPassword();

        $userInfo = '';
        if ($user) {
            $userInfo .= $user;
            $userInfo .= $password ? ':' . $password : '';
        }
        return $userInfo;
    }

    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setHost(?string $host): void
    {
        $this->host = $host;
        // set default path for URL
        $this->setPath('/');
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setPort(?int $port): void
    {
        $this->port = $port;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setQuery(ParametersInterface $query): void
    {
        $this->query = $query;
    }

    public function getQuery(): ParametersInterface
    {
        if (empty($this->query)) {
            $this->query = new Parameters();
        }
        return $this->query;
    }

    public function setFragment(?string $fragment): void
    {
        $this->fragment = $fragment;
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }
    
    public function toString(): string
    {
        // URI = scheme:[//authority]path[?query][#fragment]
        // authority = [userinfo@]host[:port]

        $scheme = (string) $this->getScheme();
        $authority = (string) $this->getAuthority();
        $path = (string) $this->getPath();
        $query = (string) $this->getQuery();
        $fragment = (string) $this->getFragment();

        $uriString = "$scheme:";
        $uriString .= $authority ? "//$authority" : '';
        $uriString .= $path;
        $uriString .= $query ? "?$query" : '';
        $uriString .= $fragment ?  "#$fragment" : '';

        return $uriString;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}