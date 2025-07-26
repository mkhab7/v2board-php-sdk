<?php

namespace Mkhab7\V2Board\SDK\Config;

class Config
{
    private string $baseUrl;
    private array $defaultHeaders;
    private int $timeout;
    private bool $verifySsl;
    private string $apiVersion;

    public function __construct(
        string $baseUrl = '',
        array $defaultHeaders = [],
        int $timeout = 30,
        bool $verifySsl = true,
        string $apiVersion = 'v1'
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->defaultHeaders = array_merge([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $defaultHeaders);
        $this->timeout = $timeout;
        $this->verifySsl = $verifySsl;
        $this->apiVersion = $apiVersion;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    public function getDefaultHeaders(): array
    {
        return $this->defaultHeaders;
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

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function shouldVerifySsl(): bool
    {
        return $this->verifySsl;
    }

    public function setVerifySsl(bool $verifySsl): self
    {
        $this->verifySsl = $verifySsl;
        return $this;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    public function getApiBasePath(): string
    {
        return "/api/{$this->apiVersion}";
    }

    public function getFullApiUrl(string $endpoint = ''): string
    {
        $endpoint = ltrim($endpoint, '/');
        return $this->baseUrl . $this->getApiBasePath() . '/' . $endpoint;
    }
} 