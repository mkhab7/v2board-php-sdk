<?php

namespace Mkhab7\V2Board\SDK\Api;

use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Exceptions\HttpException;

class Plan
{
    private HttpClientInterface $httpClient;
    private string $nodeId;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->nodeId = '';
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

    public function fetch(): array
    {
        if (empty($this->nodeId)) {
            throw new \InvalidArgumentException('Node ID is required');
        }

        $response = $this->httpClient->get("/{$this->nodeId}/plan/fetch");
        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'])) {
            throw new HttpException('Invalid response format');
        }

        return $data['data'];
    }

    public function save(array $planData): array
    {
        if (empty($this->nodeId)) {
            throw new \InvalidArgumentException('Node ID is required');
        }

        $response = $this->httpClient->post("/{$this->nodeId}/plan/save", $planData);
        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'])) {
            throw new HttpException('Invalid response format');
        }

        return $data;
    }

    public function getPlans(): array
    {
        return $this->fetch();
    }

    public function getPlanById(int $id): ?array
    {
        $plans = $this->fetch();
        foreach ($plans as $plan) {
            if ($plan['id'] === $id) {
                return $plan;
            }
        }
        return null;
    }

    public function getPlanByName(string $name): ?array
    {
        $plans = $this->fetch();
        foreach ($plans as $plan) {
            if ($plan['name'] === $name) {
                return $plan;
            }
        }
        return null;
    }

    public function getPlansByGroupId(int $groupId): array
    {
        $plans = $this->fetch();
        return array_filter($plans, fn($plan) => $plan['group_id'] === $groupId);
    }

    public function getVisiblePlans(): array
    {
        $plans = $this->fetch();
        return array_filter($plans, fn($plan) => $plan['show'] === 1);
    }

    public function getPlanCount(): int
    {
        $plans = $this->fetch();
        return count($plans);
    }

    public function getTotalUserCount(): int
    {
        $plans = $this->fetch();
        return array_sum(array_column($plans, 'count'));
    }
} 