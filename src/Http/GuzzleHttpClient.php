<?php

namespace Mkhab7\V2Board\SDK\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Mkhab7\V2Board\SDK\Config\Config;
use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Exceptions\HttpException;

class GuzzleHttpClient implements HttpClientInterface
{
    private GuzzleClient $client;
    private Config $config;
    private ?string $authToken = null;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new GuzzleClient([
            'base_uri' => $config->getBaseUrl(),
            'timeout' => $config->getTimeout(),
            'verify' => $config->shouldVerifySsl(),
            'headers' => $config->getDefaultHeaders(),
            'cookies' => true
        ]);
    }
    
    public function send(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($request);
        } catch (GuzzleException $e) {
            throw new HttpException(
                "HTTP request failed: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    public function get(string $uri, array $headers = []): ResponseInterface
    {
        $fullUri = $this->buildFullUri($uri);
        $headers = $this->addAuthHeader($headers);
        $request = new Request('GET', $fullUri, $headers);
        return $this->send($request);
    }
    
    public function post(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        $fullUri = $this->buildFullUri($uri);
        $body = http_build_query($data);
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $headers = $this->addAuthHeader($headers);
        
        $request = new Request('POST', $fullUri, $headers, $body);
        return $this->send($request);
    }
    
    private function buildFullUri(string $uri): string
    {
        // If URI already starts with http, return as is
        if (str_starts_with($uri, 'http')) {
            return $uri;
        }
        
        // If URI starts with /api/, use as is
        if (str_starts_with($uri, '/api/')) {
            return $this->config->getBaseUrl() . $uri;
        }
        
        // If URI starts with /monitor/, use as is (for stats)
        if (str_starts_with($uri, '/monitor/')) {
            return $this->config->getBaseUrl() . $uri;
        }
        
        // Otherwise, prepend API base path
        return $this->config->getFullApiUrl($uri);
    }
    
    public function getClient(): GuzzleClient
    {
        return $this->client;
    }
    
    /**
     * Set authentication token for subsequent requests
     */
    public function setAuthToken(string $token): self
    {
        $this->authToken = $token;
        return $this;
    }
    
    /**
     * Get current authentication token
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }
    
    /**
     * Clear authentication token
     */
    public function clearAuthToken(): self
    {
        $this->authToken = null;
        return $this;
    }
    
    /**
     * Add authorization header if token is set
     */
    private function addAuthHeader(array $headers): array
    {
        if ($this->authToken) {
            $headers['Authorization'] = $this->authToken;
        }
        return $headers;
    }
} 