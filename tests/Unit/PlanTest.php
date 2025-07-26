<?php

use Mkhab7\V2Board\SDK\Api\Plan;
use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;

test('plan class exists and has required methods', function () {
    $httpClient = Mockery::mock(HttpClientInterface::class);
    $plan = new Plan($httpClient);
    
    expect($plan)->toBeInstanceOf(Plan::class);
    expect(method_exists($plan, 'setNodeId'))->toBeTrue();
    expect(method_exists($plan, 'getNodeId'))->toBeTrue();
    expect(method_exists($plan, 'fetch'))->toBeTrue();
    expect(method_exists($plan, 'save'))->toBeTrue();
    expect(method_exists($plan, 'getPlans'))->toBeTrue();
    expect(method_exists($plan, 'getPlanById'))->toBeTrue();
    expect(method_exists($plan, 'getPlanByName'))->toBeTrue();
    expect(method_exists($plan, 'getPlansByGroupId'))->toBeTrue();
    expect(method_exists($plan, 'getVisiblePlans'))->toBeTrue();
    expect(method_exists($plan, 'getPlanCount'))->toBeTrue();
    expect(method_exists($plan, 'getTotalUserCount'))->toBeTrue();
});

test('plan setNodeId returns self for chaining', function () {
    $httpClient = Mockery::mock(HttpClientInterface::class);
    $plan = new Plan($httpClient);
    
    $result = $plan->setNodeId('test-node-id');
    expect($result)->toBe($plan);
    expect($plan->getNodeId())->toBe('test-node-id');
});

test('plan initial state has empty nodeId', function () {
    $httpClient = Mockery::mock(HttpClientInterface::class);
    $plan = new Plan($httpClient);
    
    expect($plan->getNodeId())->toBe('');
}); 