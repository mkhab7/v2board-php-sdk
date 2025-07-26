<?php

use Mkhab7\V2Board\SDK\Api\ServerGroup;
use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;

test('server group class exists and has required methods', function () {
    $httpClient = Mockery::mock(HttpClientInterface::class);
    $serverGroup = new ServerGroup($httpClient);
    
    expect($serverGroup)->toBeInstanceOf(ServerGroup::class);
    expect(method_exists($serverGroup, 'setNodeId'))->toBeTrue();
    expect(method_exists($serverGroup, 'getNodeId'))->toBeTrue();
    expect(method_exists($serverGroup, 'fetch'))->toBeTrue();
    expect(method_exists($serverGroup, 'getGroups'))->toBeTrue();
    expect(method_exists($serverGroup, 'getGroupById'))->toBeTrue();
    expect(method_exists($serverGroup, 'getGroupByName'))->toBeTrue();
    expect(method_exists($serverGroup, 'getGroupCount'))->toBeTrue();
    expect(method_exists($serverGroup, 'getTotalUserCount'))->toBeTrue();
    expect(method_exists($serverGroup, 'getTotalServerCount'))->toBeTrue();
});

test('server group setNodeId returns self for chaining', function () {
    $httpClient = Mockery::mock(HttpClientInterface::class);
    $serverGroup = new ServerGroup($httpClient);
    
    $result = $serverGroup->setNodeId('test-node-id');
    expect($result)->toBe($serverGroup);
    expect($serverGroup->getNodeId())->toBe('test-node-id');
});

test('server group initial state has empty nodeId', function () {
    $httpClient = Mockery::mock(HttpClientInterface::class);
    $serverGroup = new ServerGroup($httpClient);
    
    expect($serverGroup->getNodeId())->toBe('');
}); 