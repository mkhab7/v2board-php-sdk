<?php

namespace Mkhab7\V2Board\SDK\Api;

use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Exceptions\HttpException;

class User
{
    private HttpClientInterface $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    
    /**
     * Get user information
     */
    public function getInfo(): array
    {
        try {
            $response = $this->httpClient->get('/user/info');
            $data = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() !== 200) {
                throw new HttpException(
                    'Failed to get user info',
                    $response->getStatusCode()
                );
            }
            
            return $data['data'] ?? [];
        } catch (HttpException $e) {
            throw new HttpException(
                'Failed to get user info: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    /**
     * Get user email
     */
    public function getEmail(): string
    {
        $info = $this->getInfo();
        return $info['email'] ?? '';
    }
    
    /**
     * Get transfer enable (in bytes)
     */
    public function getTransferEnable(): int
    {
        $info = $this->getInfo();
        return $info['transfer_enable'] ?? 0;
    }
    
    /**
     * Get transfer enable in GB
     */
    public function getTransferEnableGB(): float
    {
        return $this->getTransferEnable() / (1024 * 1024 * 1024);
    }
    
    /**
     * Get device limit
     */
    public function getDeviceLimit(): ?int
    {
        $info = $this->getInfo();
        return $info['device_limit'] ?? null;
    }
    
    /**
     * Get last login time
     */
    public function getLastLoginAt(): ?string
    {
        $info = $this->getInfo();
        return $info['last_login_at'] ?? null;
    }
    
    /**
     * Get created at timestamp
     */
    public function getCreatedAt(): int
    {
        $info = $this->getInfo();
        return $info['created_at'] ?? 0;
    }
    
    /**
     * Check if user is banned
     */
    public function isBanned(): bool
    {
        $info = $this->getInfo();
        return ($info['banned'] ?? 0) === 1;
    }
    
    /**
     * Check if auto renewal is enabled
     */
    public function isAutoRenewal(): bool
    {
        $info = $this->getInfo();
        return ($info['auto_renewal'] ?? 0) === 1;
    }
    
    /**
     * Get expired at timestamp
     */
    public function getExpiredAt(): int
    {
        $info = $this->getInfo();
        return $info['expired_at'] ?? 0;
    }
    
    /**
     * Get balance
     */
    public function getBalance(): float
    {
        $info = $this->getInfo();
        return $info['balance'] ?? 0;
    }
    
    /**
     * Get commission balance
     */
    public function getCommissionBalance(): float
    {
        $info = $this->getInfo();
        return $info['commission_balance'] ?? 0;
    }
    
    /**
     * Get plan ID
     */
    public function getPlanId(): int
    {
        $info = $this->getInfo();
        return $info['plan_id'] ?? 0;
    }
    
    /**
     * Get UUID
     */
    public function getUuid(): string
    {
        $info = $this->getInfo();
        return $info['uuid'] ?? '';
    }
    
    /**
     * Get avatar URL
     */
    public function getAvatarUrl(): string
    {
        $info = $this->getInfo();
        return $info['avatar_url'] ?? '';
    }
} 