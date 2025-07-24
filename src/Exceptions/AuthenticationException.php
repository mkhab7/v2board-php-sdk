<?php

namespace Mkhab7\V2Board\SDK\Exceptions;

class AuthenticationException extends V2BoardException
{
    public function __construct(string $message = "Authentication failed", int $code = 401, ?\Exception $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous, $context);
    }
} 