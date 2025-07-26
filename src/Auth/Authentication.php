<?php

namespace Mkhab7\V2Board\SDK\Auth;

use Mkhab7\V2Board\SDK\Contracts\AuthInterface;
use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Exceptions\AuthenticationException;
use Mkhab7\V2Board\SDK\Exceptions\HttpException;

class Authentication implements AuthInterface
{
    private HttpClientInterface $httpClient;
    private ?string $token = null;
    private ?array $userData = null;
    private ?bool $isAdmin = null;
    private ?string $authData = null;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    
    public function login(string $email, string $password): array
    {
        try {
                    $response = $this->httpClient->post('/passport/auth/login', [
            'email' => $email,
            'password' => $password
        ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() !== 200) {
                throw new AuthenticationException(
                    $data['message'] ?? 'Authentication failed',
                    $response->getStatusCode(),
                    null,
                    ['response' => $data]
                );
            }
            
            if (!isset($data['data']['token'])) {
                throw new AuthenticationException(
                    'No authentication token received',
                    401,
                    null,
                    ['response' => $data]
                );
            }
            
            $this->token = $data['data']['token'];
            $this->isAdmin = $data['data']['is_admin'] ?? false;
            $this->authData = $data['data']['auth_data'] ?? null;
            $this->userData = $data['data'] ?? [];
            
            // Set auth token in HTTP client for subsequent requests
            if ($this->authData) {
                $this->httpClient->setAuthToken($this->authData);
            }
            
            return $data;
            
        } catch (HttpException $e) {
            throw new AuthenticationException(
                'Network error during authentication: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    public function getToken(): ?string
    {
        return $this->token;
    }
    
    public function isAuthenticated(): bool
    {
        return $this->token !== null;
    }
    
    public function logout(): void
    {
        $this->token = null;
        $this->userData = null;
        $this->isAdmin = null;
        $this->authData = null;
        $this->httpClient->clearAuthToken();
    }
    
    public function getUserData(): ?array
    {
        return $this->userData;
    }
    
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }
    
    /**
     * Check if the authenticated user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin ?? false;
    }
    
    /**
     * Get the auth data (JWT token)
     */
    public function getAuthData(): ?string
    {
        return $this->authData;
    }
    
    /**
     * Set admin status
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }
    
    /**
     * Set auth data
     */
    public function setAuthData(string $authData): self
    {
        $this->authData = $authData;
        $this->httpClient->setAuthToken($authData);
        return $this;
    }
} 