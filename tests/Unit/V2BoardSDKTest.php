<?php

use Mkhab7\V2Board\SDK\V2BoardSDK;
use Mkhab7\V2Board\SDK\Contracts\AuthInterface;
use Mkhab7\V2Board\SDK\Contracts\HttpClientInterface;

test('sdk can be instantiated', function () {
    $sdk = new V2BoardSDK();
    
    expect($sdk)->toBeInstanceOf(V2BoardSDK::class);
});

test('sdk can be instantiated with base url', function () {
    $sdk = new V2BoardSDK('https://example.com');
    
    expect($sdk->config()->getBaseUrl())->toBe('https://example.com');
});

test('sdk can be instantiated with custom config', function () {
    $sdk = new V2BoardSDK('https://example.com', [
        'timeout' => 60,
        'verify_ssl' => false,
        'headers' => ['X-Custom' => 'value']
    ]);
    
    expect($sdk->config()->getTimeout())->toBe(60);
    expect($sdk->config()->shouldVerifySsl())->toBeFalse();
    expect($sdk->config()->getDefaultHeaders())->toHaveKey('X-Custom');
});

test('auth method returns AuthInterface', function () {
    $sdk = new V2BoardSDK();
    
    expect($sdk->auth())->toBeInstanceOf(AuthInterface::class);
});

test('http method returns HttpClientInterface', function () {
    $sdk = new V2BoardSDK();
    
    expect($sdk->http())->toBeInstanceOf(HttpClientInterface::class);
});

test('initial authentication state is false', function () {
    $sdk = new V2BoardSDK();
    
    expect($sdk->isAuthenticated())->toBeFalse();
    expect($sdk->getToken())->toBeNull();
    expect($sdk->isAdmin())->toBeFalse();
    expect($sdk->getAuthData())->toBeNull();
});

test('logout clears authentication state', function () {
    $sdk = new V2BoardSDK();
    $sdk->auth()->setToken('test-token');
    $sdk->auth()->setIsAdmin(true);
    $sdk->auth()->setAuthData('test-auth-data');
    $sdk->logout();
    
    expect($sdk->isAuthenticated())->toBeFalse();
    expect($sdk->getToken())->toBeNull();
    expect($sdk->isAdmin())->toBeFalse();
    expect($sdk->getAuthData())->toBeNull();
});

test('isAdmin returns correct admin status', function () {
    $sdk = new V2BoardSDK();
    $sdk->auth()->setIsAdmin(true);
    
    expect($sdk->isAdmin())->toBeTrue();
});

test('getAuthData returns correct auth data', function () {
    $sdk = new V2BoardSDK();
    $authData = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.test';
    $sdk->auth()->setAuthData($authData);
    
    expect($sdk->getAuthData())->toBe($authData);
}); 