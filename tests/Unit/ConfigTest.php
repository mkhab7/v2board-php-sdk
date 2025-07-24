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
    expect($headers['Content-Type'])->toBe('application/x-www-form-urlencoded');
}); 