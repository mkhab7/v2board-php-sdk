<?php

namespace Mkhab7\V2Board\SDK\Api;

use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;
use Mkhab7\V2Board\SDK\Exceptions\HttpException;

class Stats
{
    private HttpClientInterface $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    
    /**
     * Get system statistics
     */
    public function getStats(): array
    {
        try {
            $response = $this->httpClient->get('/monitor/api/stats');
            $data = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() !== 200) {
                throw new HttpException(
                    'Failed to get stats',
                    $response->getStatusCode()
                );
            }
            
            return $data;
        } catch (HttpException $e) {
            throw new HttpException(
                'Failed to get system stats: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    /**
     * Get failed jobs count
     */
    public function getFailedJobs(): int
    {
        $stats = $this->getStats();
        return $stats['failedJobs'] ?? 0;
    }
    
    /**
     * Get jobs per minute
     */
    public function getJobsPerMinute(): int
    {
        $stats = $this->getStats();
        return $stats['jobsPerMinute'] ?? 0;
    }
    
    /**
     * Get paused masters count
     */
    public function getPausedMasters(): int
    {
        $stats = $this->getStats();
        return $stats['pausedMasters'] ?? 0;
    }
    
    /**
     * Get processes count
     */
    public function getProcesses(): int
    {
        $stats = $this->getStats();
        return $stats['processes'] ?? 0;
    }
    
    /**
     * Get system status
     */
    public function getStatus(): string
    {
        $stats = $this->getStats();
        return $stats['status'] ?? 'unknown';
    }
    
    /**
     * Get recent jobs count
     */
    public function getRecentJobs(): int
    {
        $stats = $this->getStats();
        return $stats['recentJobs'] ?? 0;
    }
} 