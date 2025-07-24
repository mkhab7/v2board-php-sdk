<?php

use Mkhab7\V2Board\SDK\Api\User;
use Mkhab7\V2Board\SDK\Config\Config;
use Mkhab7\V2Board\SDK\Http\GuzzleHttpClient;

beforeEach(function () {
    $config = new Config('https://sr3.x-upload.org');
    $this->httpClient = new GuzzleHttpClient($config);
    $this->user = new User($this->httpClient);
});

test('user can be instantiated', function () {
    expect($this->user)->toBeInstanceOf(User::class);
});

test('user has required methods', function () {
    expect(method_exists($this->user, 'getInfo'))->toBeTrue();
    expect(method_exists($this->user, 'getEmail'))->toBeTrue();
    expect(method_exists($this->user, 'getTransferEnable'))->toBeTrue();
    expect(method_exists($this->user, 'getTransferEnableGB'))->toBeTrue();
    expect(method_exists($this->user, 'getDeviceLimit'))->toBeTrue();
    expect(method_exists($this->user, 'getLastLoginAt'))->toBeTrue();
    expect(method_exists($this->user, 'getCreatedAt'))->toBeTrue();
    expect(method_exists($this->user, 'isBanned'))->toBeTrue();
    expect(method_exists($this->user, 'isAutoRenewal'))->toBeTrue();
    expect(method_exists($this->user, 'getExpiredAt'))->toBeTrue();
    expect(method_exists($this->user, 'getBalance'))->toBeTrue();
    expect(method_exists($this->user, 'getCommissionBalance'))->toBeTrue();
    expect(method_exists($this->user, 'getPlanId'))->toBeTrue();
    expect(method_exists($this->user, 'getUuid'))->toBeTrue();
    expect(method_exists($this->user, 'getAvatarUrl'))->toBeTrue();
}); 