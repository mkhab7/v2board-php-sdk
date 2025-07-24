<?php

use Mkhab7\V2Board\SDK\Api\Admin;
use Mkhab7\V2Board\SDK\Config\Config;
use Mkhab7\V2Board\SDK\Http\GuzzleHttpClient;

beforeEach(function () {
    $config = new Config('https://sr3.x-upload.org');
    $this->httpClient = new GuzzleHttpClient($config);
    $this->admin = new Admin($this->httpClient);
});

test('admin can be instantiated', function () {
    expect($this->admin)->toBeInstanceOf(Admin::class);
});

test('admin has required methods', function () {
    expect(method_exists($this->admin, 'setNodeId'))->toBeTrue();
    expect(method_exists($this->admin, 'getNodeId'))->toBeTrue();
    expect(method_exists($this->admin, 'getStats'))->toBeTrue();
    expect(method_exists($this->admin, 'generateUser'))->toBeTrue();
    expect(method_exists($this->admin, 'updateUser'))->toBeTrue();
    expect(method_exists($this->admin, 'getOnlineUsers'))->toBeTrue();
    expect(method_exists($this->admin, 'getMonthIncome'))->toBeTrue();
    expect(method_exists($this->admin, 'getMonthIncomeFloat'))->toBeTrue();
    expect(method_exists($this->admin, 'getMonthRegisterTotal'))->toBeTrue();
    expect(method_exists($this->admin, 'getDayRegisterTotal'))->toBeTrue();
    expect(method_exists($this->admin, 'getTicketPendingTotal'))->toBeTrue();
    expect(method_exists($this->admin, 'getCommissionPendingTotal'))->toBeTrue();
    expect(method_exists($this->admin, 'getDayIncome'))->toBeTrue();
    expect(method_exists($this->admin, 'getLastMonthIncome'))->toBeTrue();
    expect(method_exists($this->admin, 'getCommissionMonthPayout'))->toBeTrue();
    expect(method_exists($this->admin, 'getCommissionLastMonthPayout'))->toBeTrue();
});

test('setNodeId returns self for chaining', function () {
    $result = $this->admin->setNodeId('test-node');
    
    expect($result)->toBe($this->admin);
});

test('getNodeId returns set node id', function () {
    $nodeId = 'test-node-123';
    $this->admin->setNodeId($nodeId);
    
    expect($this->admin->getNodeId())->toBe($nodeId);
}); 