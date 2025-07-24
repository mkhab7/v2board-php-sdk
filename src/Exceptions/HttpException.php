<?php

namespace Mkhab7\V2Board\SDK\Exceptions;

class HttpException extends V2BoardException
{
    protected int $statusCode;
    
    public function __construct(string $message = "HTTP request failed", int $statusCode = 500, ?\Exception $previous = null, array $context = [])
    {
        parent::__construct($message, $statusCode, $previous, $context);
        $this->statusCode = $statusCode;
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
} 