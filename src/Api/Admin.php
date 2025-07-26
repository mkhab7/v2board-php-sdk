<?php

namespace Mkhab7\V2Board\SDK\Api;

use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Exceptions\HttpException;

class Admin
{
    private HttpClientInterface $httpClient;
    private string $nodeId;
    
    public function __construct(HttpClientInterface $httpClient, string $nodeId = '')
    {
        $this->httpClient = $httpClient;
        $this->nodeId = $nodeId;
    }
    
    public function setNodeId(string $nodeId): self
    {
        $this->nodeId = $nodeId;
        return $this;
    }
    
    public function getNodeId(): string
    {
        return $this->nodeId;
    }
    
    public function getStats(): array
    {
        if (empty($this->nodeId)) {
            throw new HttpException('Node ID is required for admin operations');
        }
        
        try {
            $response = $this->httpClient->get("/{$this->nodeId}/stat/getOverride");
            $data = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() !== 200) {
                throw new HttpException(
                    'Failed to get admin stats',
                    $response->getStatusCode()
                );
            }
            
            return $data['data'] ?? [];
        } catch (HttpException $e) {
            throw new HttpException(
                'Failed to get admin stats: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    public function generateUser(array $userData): array
    {
        if (empty($this->nodeId)) {
            throw new HttpException('Node ID is required for admin operations');
        }
        
        try {
            $response = $this->httpClient->post("/{$this->nodeId}/user/generate", $userData);
            $data = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() !== 200) {
                throw new HttpException(
                    'Failed to generate user',
                    $response->getStatusCode()
                );
            }
            
            return $data;
        } catch (HttpException $e) {
            throw new HttpException(
                'Failed to generate user: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    public function updateUser(array $userData): array
    {
        if (empty($this->nodeId)) {
            throw new HttpException('Node ID is required for admin operations');
        }
        
        try {
            $response = $this->httpClient->post("/{$this->nodeId}/user/update", $userData);
            $data = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() !== 200) {
                throw new HttpException(
                    'Failed to update user',
                    $response->getStatusCode()
                );
            }
            
            return $data;
        } catch (HttpException $e) {
            throw new HttpException(
                'Failed to update user: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    public function getOnlineUsers(): int
    {
        $stats = $this->getStats();
        return $stats['online_user'] ?? 0;
    }
    
    public function getMonthIncome(): string
    {
        $stats = $this->getStats();
        return $stats['month_income'] ?? '0';
    }
    
    public function getMonthIncomeFloat(): float
    {
        return (float) $this->getMonthIncome();
    }
    
    public function getMonthRegisterTotal(): int
    {
        $stats = $this->getStats();
        return $stats['month_register_total'] ?? 0;
    }
    
    public function getDayRegisterTotal(): int
    {
        $stats = $this->getStats();
        return $stats['day_register_total'] ?? 0;
    }
    
    public function getTicketPendingTotal(): int
    {
        $stats = $this->getStats();
        return $stats['ticket_pending_total'] ?? 0;
    }
    
    public function getCommissionPendingTotal(): int
    {
        $stats = $this->getStats();
        return $stats['commission_pending_total'] ?? 0;
    }
    
    public function getDayIncome(): int
    {
        $stats = $this->getStats();
        return $stats['day_income'] ?? 0;
    }
    
    public function getLastMonthIncome(): int
    {
        $stats = $this->getStats();
        return $stats['last_month_income'] ?? 0;
    }
    
    public function getCommissionMonthPayout(): int
    {
        $stats = $this->getStats();
        return $stats['commission_month_payout'] ?? 0;
    }
    
    public function getCommissionLastMonthPayout(): int
    {
        $stats = $this->getStats();
        return $stats['commission_last_month_payout'] ?? 0;
    }

    public function fetchUserByEmail(string $email): ?array
    {
        return $this->fetchUserByFilter('email', $email, '=');
    }

    public function fetchUserById($id): ?array
    {
        return $this->fetchUserByFilter('id', $id, '=');
    }

    public function fetchUserByToken(string $token): ?array
    {
        return $this->fetchUserByFilter('token', $token, '=');
    }

    private function fetchUserByFilter(string $key, $value, string $condition = '='): ?array
    {
        if (empty($this->nodeId)) {
            throw new HttpException('Node ID is required for admin operations');
        }
        $query = http_build_query([
            'filter' => [
                [
                    'key' => $key,
                    'condition' => $condition,
                    'value' => $value,
                ]
            ],
            'pageSize' => 1,
            'current' => 1
        ]);
        $response = $this->httpClient->get("/{$this->nodeId}/user/fetch?$query");
        $data = json_decode($response->getBody()->getContents(), true);
        if (!isset($data['data']) || !is_array($data['data']) || count($data['data']) === 0) {
            return null;
        }
        return $data['data'][0];
    }
} 