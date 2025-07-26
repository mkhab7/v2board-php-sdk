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
    private bool $cacheEnabled = false;
    private string $cacheFile = __DIR__ . '/.auth_cache.json';
    private int $cacheTtl = 3600;
    private $cacheDriver = null;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function setCacheEnabled(bool $enabled): self
    {
        $this->cacheEnabled = $enabled;
        return $this;
    }

    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function setCacheFile(string $file): self
    {
        $this->cacheFile = $file;
        return $this;
    }

    public function getCacheFile(): string
    {
        return $this->cacheFile;
    }

    public function setCacheTtl(int $seconds): self
    {
        $this->cacheTtl = $seconds;
        return $this;
    }

    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }

    public function setCacheDriver(callable $driver): self
    {
        $this->cacheDriver = $driver;
        return $this;
    }

    private function loadFromCache(): bool
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        if ($this->cacheDriver) {
            $cached = call_user_func($this->cacheDriver, 'get', 'v2board-auth-data');
            if ($cached && is_array($cached)) {
                $this->token = $cached['token'];
                $this->authData = $cached['authData'] ?? null;
                $this->isAdmin = $cached['isAdmin'] ?? false;
                $this->userData = $cached['userData'] ?? null;
                if ($this->authData) {
                    $this->httpClient->setAuthToken($this->authData);
                }
                return true;
            }
            return false;
        }

        if (!file_exists($this->cacheFile)) {
            return false;
        }

        $data = json_decode(file_get_contents($this->cacheFile), true);
        if (!is_array($data) || empty($data['token'])) {
            return false;
        }

        if ($this->cacheTtl > 0 && isset($data['timestamp'])) {
            $age = time() - $data['timestamp'];
            if ($age > $this->cacheTtl) {
                $this->clearCache();
                return false;
            }
        }

        $this->token = $data['token'];
        $this->authData = $data['authData'] ?? null;
        $this->isAdmin = $data['isAdmin'] ?? false;
        $this->userData = $data['userData'] ?? null;
        if ($this->authData) {
            $this->httpClient->setAuthToken($this->authData);
        }
        return true;
    }

    private function saveToCache(): void
    {
        if (!$this->cacheEnabled) return;

        $data = [
            'token' => $this->token,
            'authData' => $this->authData,
            'isAdmin' => $this->isAdmin,
            'userData' => $this->userData,
            'timestamp' => time(),
        ];

        if ($this->cacheDriver) {
            call_user_func($this->cacheDriver, 'put', 'v2board-auth-data', $data, $this->cacheTtl);
        } else {
            file_put_contents($this->cacheFile, json_encode($data));
        }
    }

    private function clearCache(): void
    {
        if ($this->cacheDriver) {
            call_user_func($this->cacheDriver, 'forget', 'v2board-auth-data');
        } elseif (file_exists($this->cacheFile)) {
            @unlink($this->cacheFile);
        }
    }

    public function login(string $email, string $password): array
    {
        if ($this->cacheEnabled && $this->loadFromCache()) {
            return [
                'data' => [
                    'token' => $this->token,
                    'auth_data' => $this->authData,
                    'is_admin' => $this->isAdmin,
                ] + ($this->userData ?? [])
            ];
        }
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
            if ($this->authData) {
                $this->httpClient->setAuthToken($this->authData);
            }
            $this->saveToCache();
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
        $this->clearCache();
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