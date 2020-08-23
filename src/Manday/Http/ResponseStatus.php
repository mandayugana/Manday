<?php

namespace Manday\Http;

class ResponseStatus
{
    // informational responses
    public const CODE_100 = 100;
    public const PHRASE_100 = 'Continue';
    public const CODE_101 = 101;
    public const PHRASE_101 = 'Switching Protocols';
    public const CODE_102 = 102;
    public const PHRASE_102 = 'Processing';
    public const CODE_103 = 103;
    public const PHRASE_103 = 'Early Hints';

    // success responses
    public const CODE_200 = 200;
    public const PHRASE_200 = 'OK';
    public const CODE_201 = 201;
    public const PHRASE_201 = 'Created';
    public const CODE_202 = 202;
    public const PHRASE_202 = 'Accepted';
    public const CODE_203 = 203;
    public const PHRASE_203 = 'Non-Authoritative Information';
    public const CODE_204 = 204;
    public const PHRASE_204 = 'No Content';
    public const CODE_205 = 205;
    public const PHRASE_205 = 'Reset Content';
    public const CODE_206 = 206;
    public const PHRASE_206 = 'Partial Content';
    public const CODE_207 = 207;
    public const PHRASE_207 = 'Multi-status';
    public const CODE_208 = 208;
    public const PHRASE_208 = 'Already Reported';
    public const CODE_226 = 226;
    public const PHRASE_226 = 'IM Used';

    // redirection messages
    public const CODE_300 = 300;
    public const PHRASE_300 = 'Multiple Choices';
    public const CODE_301 = 301;
    public const PHRASE_301 = 'Moved Permanently';
    public const CODE_302 = 302;
    public const PHRASE_302 = 'Found';
    public const CODE_303 = 303;
    public const PHRASE_303 = 'See Other';
    public const CODE_304 = 304;
    public const PHRASE_304 = 'Not Modified';
    public const CODE_305 = 305;
    public const PHRASE_305 = 'Use Proxy';
    public const CODE_306 = 306;
    public const PHRASE_306 = 'Switch Proxy'; // Deprecated
    public const CODE_307 = 307;
    public const PHRASE_307 = 'Temporary Redirect';
    public const CODE_308 = 308;
    public const PHRASE_308 = 'Permanent Redirect';

    // client error responses
    public const CODE_400 = 400;
    public const PHRASE_400 = 'Bad Request';
    public const CODE_401 = 401;
    public const PHRASE_401 = 'Unauthorized';
    public const CODE_402 = 402;
    public const PHRASE_402 = 'Payment Required';
    public const CODE_403 = 403;
    public const PHRASE_403 = 'Forbidden';
    public const CODE_404 = 404;
    public const PHRASE_404 = 'Not Found';
    public const CODE_405 = 405;
    public const PHRASE_405 = 'Method Not Allowed';
    public const CODE_406 = 406;
    public const PHRASE_406 = 'Not Acceptable';
    public const CODE_407 = 407;
    public const PHRASE_407 = 'Proxy Authentication Required';
    public const CODE_408 = 408;
    public const PHRASE_408 = 'Request Timeout';
    public const CODE_409 = 409;
    public const PHRASE_409 = 'Conflict';
    public const CODE_410 = 410;
    public const PHRASE_410 = 'Gone';
    public const CODE_411 = 411;
    public const PHRASE_411 = 'Length Required';
    public const CODE_412 = 412;
    public const PHRASE_412 = 'Precondition Failed';

    // client error responses
    public const CODE_413 = 413;
    public const PHRASE_413 = 'Payload Too Large';
    public const CODE_414 = 414;
    public const PHRASE_414 = 'URI Too Long';
    public const CODE_415 = 415;
    public const PHRASE_415 = 'Unsupported Media Type';
    public const CODE_416 = 416;
    public const PHRASE_416 = 'Range Not Satisfiable';
    public const CODE_417 = 417;
    public const PHRASE_417 = 'Expectation Failed';
    public const CODE_418 = 418;
    public const PHRASE_418 = 'I\'m a teapot';
    public const CODE_421 = 421;
    public const PHRASE_421 = 'Misdirected Request';
    public const CODE_422 = 422;
    public const PHRASE_422 = 'Unprocessable Entity';
    public const CODE_423 = 423;
    public const PHRASE_423 = 'Locked';
    public const CODE_424 = 424;
    public const PHRASE_424 = 'Failed Dependency';
    public const CODE_425 = 425;
    public const PHRASE_425 = 'Too Early';
    public const CODE_426 = 426;
    public const PHRASE_426 = 'Upgrade Required';
    public const CODE_428 = 428;
    public const PHRASE_428 = 'Precondition Required';
    public const CODE_429 = 429;
    public const PHRASE_429 = 'Too Many Requests';
    public const CODE_431 = 431;
    public const PHRASE_431 = 'Request Header Fields Too Large';
    public const CODE_451 = 451;
    public const PHRASE_451 = 'Unavailable For Legal Reasons';

    // server error responses
    public const CODE_500 = 500;
    public const PHRASE_500 = 'Internal Server Error';
    public const CODE_501 = 501;
    public const PHRASE_501 = 'Not Implemented';
    public const CODE_502 = 502;
    public const PHRASE_502 = 'Bad Gateway';
    public const CODE_503 = 503;
    public const PHRASE_503 = 'Service Unavailable';
    public const CODE_504 = 504;
    public const PHRASE_504 = 'Gateway Timeout';
    public const CODE_505 = 505;
    public const PHRASE_505 = 'HTTP Version Not Supported';
    public const CODE_506 = 506;
    public const PHRASE_506 = 'Variant Also Negotiates';
    public const CODE_507 = 507;
    public const PHRASE_507 = 'Insufficient Storage';
    public const CODE_508 = 508;
    public const PHRASE_508 = 'Loop Detected';
    public const CODE_510 = 510;
    public const PHRASE_510 = 'Not Extended';
    public const CODE_511 = 511;
    public const PHRASE_511 = 'Network Authentication Required';
    public const CODE_599 = 599;
    public const PHRASE_599 = 'Network Connect Timeout Error';

    public const MIN_STATUS_CODE = 100;
    public const MAX_STATUS_CODE = 599;

    public static function getPhrase(int $code): string
    {
        $constantName = "static::PHRASE_$code";
        return defined($constantName) ? constant($constantName) : '';
    }
}
