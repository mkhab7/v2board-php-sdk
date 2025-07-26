<?php

namespace Mkhab7\V2Board\SDK\Api;

use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Exceptions\HttpException;

class ServerGroup
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

        $response = $this->httpClient->get("/{$this->nodeId}/server/group/fetch");
        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'])) {
            throw new HttpException('Invalid response format');
        }

        return $data['data'];
    }

    public function getGroups(): array
    {
        return $this->fetch();
    }

    public function getGroupById(int $id): ?array
    {
        $groups = $this->fetch();
        foreach ($groups as $group) {
            if ($group['id'] === $id) {
                return $group;
            }
        }
        return null;
    }

    public function getGroupByName(string $name): ?array
    {
        $groups = $this->fetch();
        foreach ($groups as $group) {
            if ($group['name'] === $name) {
                return $group;
            }
        }
        return null;
    }

    public function getGroupCount(): int
    {
        $groups = $this->fetch();
        return count($groups);
    }

    public function getTotalUserCount(): int
    {
        $groups = $this->fetch();
        return array_sum(array_column($groups, 'user_count'));
    }

    public function getTotalServerCount(): int
    {
        $groups = $this->fetch();
        return array_sum(array_column($groups, 'server_count'));
    }
} 