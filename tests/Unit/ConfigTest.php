<?php

use Mkhab7\V2Board\SDK\Config\Config;

test('config can be created with default values', function () {
    $config = new Config();
    
    expect($config->getBaseUrl())->toBe('');
    expect($config->getTimeout())->toBe(30);
    expect($config->shouldVerifySsl())->toBeTrue();
});

test('config can be created with custom values', function () {
    $config = new Config(
        'https://example.com',
        ['Custom-Header' => 'value'],
        60,
        false
    );
    
    expect($config->getBaseUrl())->toBe('https://example.com');
    expect($config->getTimeout())->toBe(60);
    expect($config->shouldVerifySsl())->toBeFalse();
    expect($config->getDefaultHeaders())->toHaveKey('Custom-Header');
});

test('setBaseUrl trims trailing slash', function () {
    $config = new Config();
    $config->setBaseUrl('https://example.com/');
    
    expect($config->getBaseUrl())->toBe('https://example.com');
});

test('addHeader adds new header', function () {
    $config = new Config();
    $config->addHeader('X-Custom', 'value');
    
    expect($config->getDefaultHeaders())->toHaveKey('X-Custom');
    expect($config->getDefaultHeaders()['X-Custom'])->toBe('value');
});

test('removeHeader removes header', function () {
    $config = new Config();
    $config->addHeader('X-Custom', 'value');
    $config->removeHeader('X-Custom');
    
    expect($config->getDefaultHeaders())->not->toHaveKey('X-Custom');
});

test('default headers are set correctly', function () {
    $config = new Config();
    $headers = $config->getDefaultHeaders();
    
    expect($headers)->toHaveKey('Accept');
    expect($headers)->toHaveKey('Content-Type');
    expect($headers['Content-Type'])->toBe('application/json');
});

test('api version is set correctly', function () {
    $config = new Config('https://example.com', [], 30, true, 'v2');
    expect($config->getApiVersion())->toBe('v2');
});

test('api version defaults to v1', function () {
    $config = new Config('https://example.com');
    expect($config->getApiVersion())->toBe('v1');
});

test('setApiVersion changes version', function () {
    $config = new Config('https://example.com');
    $config->setApiVersion('v3');
    expect($config->getApiVersion())->toBe('v3');
});

test('getApiBasePath returns correct path', function () {
    $config = new Config('https://example.com', [], 30, true, 'v2');
    expect($config->getApiBasePath())->toBe('/api/v2');
});

test('getFullApiUrl returns correct URL', function () {
    $config = new Config('https://example.com', [], 30, true, 'v2');
    expect($config->getFullApiUrl('user/info'))->toBe('https://example.com/api/v2/user/info');
}); 