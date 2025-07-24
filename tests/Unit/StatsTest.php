<?php

use Mkhab7\V2Board\SDK\Api\Stats;
use Mkhab7\V2Board\SDK\Config\Config;
use Mkhab7\V2Board\SDK\Http\GuzzleHttpClient;

beforeEach(function () {
    $config = new Config('https://sr3.x-upload.org');
    $this->httpClient = new GuzzleHttpClient($config);
    $this->stats = new Stats($this->httpClient);
});

test('stats can be instantiated', function () {
    expect($this->stats)->toBeInstanceOf(Stats::class);
});

test('stats has required methods', function () {
    expect(method_exists($this->stats, 'getStats'))->toBeTrue();
    expect(method_exists($this->stats, 'getFailedJobs'))->toBeTrue();
    expect(method_exists($this->stats, 'getJobsPerMinute'))->toBeTrue();
    expect(method_exists($this->stats, 'getPausedMasters'))->toBeTrue();
    expect(method_exists($this->stats, 'getProcesses'))->toBeTrue();
    expect(method_exists($this->stats, 'getStatus'))->toBeTrue();
    expect(method_exists($this->stats, 'getRecentJobs'))->toBeTrue();
}); 