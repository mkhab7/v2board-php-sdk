<?php

namespace Mkhab7\V2Board\SDK\Contracts;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * Send an HTTP request and return the response
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Mkhab7\V2Board\SDK\Exceptions\HttpException
     */
    public function send(RequestInterface $request): ResponseInterface;
    
    /**
     * Create and send a GET request
     *
     * @param string $uri
     * @param array $headers
     * @return ResponseInterface
     */
    public function get(string $uri, array $headers = []): ResponseInterface;
    
    /**
     * Create and send a POST request
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     */
    public function post(string $uri, array $data = [], array $headers = []): ResponseInterface;
} 