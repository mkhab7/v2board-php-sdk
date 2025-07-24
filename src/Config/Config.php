<?php

namespace Mkhab7\V2Board\SDK\Config;

class Config
{
    private string $baseUrl;
    private array $defaultHeaders;
    private int $timeout;
    private bool $verifySsl;
    
    public function __construct(
        string $baseUrl = '',
        array $defaultHeaders = [],
        int $timeout = 30,
        bool $verifySsl = true
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->defaultHeaders = array_merge([
            'Accept' => '*/*',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Sec-Ch-Ua' => '"Not)A;Brand";v="8", "Chromium";v="138", "Google Chrome";v="138"',
            'Sec-Ch-Ua-Mobile' => '?1',
            'Sec-Ch-Ua-Platform' => '"Android"',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin'
        ], $defaultHeaders);
        $this->timeout = $timeout;
        $this->verifySsl = $verifySsl;
    }
    
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    
    public function getDefaultHeaders(): array
    {
        return $this->defaultHeaders;
    }
    
    public function getTimeout(): int
    {
        return $this->timeout;
    }
    
    public function shouldVerifySsl(): bool
    {
        return $this->verifySsl;
    }
    
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }
    
    public function addHeader(string $key, string $value): self
    {
        $this->defaultHeaders[$key] = $value;
        return $this;
    }
    
    public function removeHeader(string $key): self
    {
        unset($this->defaultHeaders[$key]);
        return $this;
    }
} 