<?php

namespace Mkhab7\V2Board\SDK;

use Mkhab7\V2Board\SDK\Auth\Authentication;
use Mkhab7\V2Board\SDK\Api\Stats;
use Mkhab7\V2Board\SDK\Api\User;
use Mkhab7\V2Board\SDK\Api\Admin;
use Mkhab7\V2Board\SDK\Api\Plan;
use Mkhab7\V2Board\SDK\Api\ServerGroup;
use Mkhab7\V2Board\SDK\Config\Config;
use Mkhab7\V2Board\SDK\Contracts\AuthInterface;
use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Http\GuzzleHttpClient;

class V2BoardSDK
{
    private Config $config;
    private HttpClientInterface $httpClient;
    private AuthInterface $auth;
    private Stats $stats;
    private User $user;
    private Admin $admin;
    private Plan $plan;
    private ServerGroup $serverGroup;
    
    public function __construct(
        string $baseUrl = '',
        array $config = []
    ) {
        $this->config = new Config(
            $baseUrl, 
            $config['headers'] ?? [], 
            $config['timeout'] ?? 30, 
            $config['verify_ssl'] ?? true,
            $config['api_version'] ?? 'v1'
        );
        $this->httpClient = new GuzzleHttpClient($this->config);
        $this->auth = new Authentication($this->httpClient);
        $this->stats = new Stats($this->httpClient);
        $this->user = new User($this->httpClient);
        $this->admin = new Admin($this->httpClient);
        $this->plan = new Plan($this->httpClient);
        $this->serverGroup = new ServerGroup($this->httpClient);
    }
    
    public function auth(): AuthInterface
    {
        return $this->auth;
    }
    
    public function http(): HttpClientInterface
    {
        return $this->httpClient;
    }
    
    public function config(): Config
    {
        return $this->config;
    }
    
    public function stats(): Stats
    {
        return $this->stats;
    }
    
    public function user(): User
    {
        return $this->user;
    }
    
    public function admin(): Admin
    {
        return $this->admin;
    }
    
    public function plan(): Plan
    {
        return $this->plan;
    }
    
    public function serverGroup(): ServerGroup
    {
        return $this->serverGroup;
    }
    
    public function setNodeId(string $nodeId): self
    {
        $this->admin->setNodeId($nodeId);
        $this->plan->setNodeId($nodeId);
        $this->serverGroup->setNodeId($nodeId);
        return $this;
    }
    
    public function generateUser(array $userData): array
    {
        return $this->admin->generateUser($userData);
    }
    
    public function updateUser(array $userData): array
    {
        return $this->admin->updateUser($userData);
    }
    
    public function login(string $email, string $password): array
    {
        return $this->auth->login($email, $password);
    }
    
    public function isAuthenticated(): bool
    {
        return $this->auth->isAuthenticated();
    }
    
    public function getToken(): ?string
    {
        return $this->auth->getToken();
    }
    
    public function isAdmin(): bool
    {
        return $this->auth->isAdmin();
    }
    
    public function getAuthData(): ?string
    {
        return $this->auth->getAuthData();
    }
    
    public function setApiVersion(string $version): self
    {
        $this->config->setApiVersion($version);
        return $this;
    }
    
    public function getApiVersion(): string
    {
        return $this->config->getApiVersion();
    }
    
    public function logout(): void
    {
        $this->auth->logout();
    }
} 