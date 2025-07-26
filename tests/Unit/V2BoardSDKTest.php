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

test('sdk has all required API methods', function () {
    $sdk = new V2BoardSDK();
    
    expect(method_exists($sdk, 'stats'))->toBeTrue();
    expect(method_exists($sdk, 'user'))->toBeTrue();
    expect(method_exists($sdk, 'admin'))->toBeTrue();
    expect(method_exists($sdk, 'plan'))->toBeTrue();
    expect(method_exists($sdk, 'serverGroup'))->toBeTrue();
    expect(method_exists($sdk, 'setNodeId'))->toBeTrue();
    expect(method_exists($sdk, 'generateUser'))->toBeTrue();
    expect(method_exists($sdk, 'updateUser'))->toBeTrue();
    expect(method_exists($sdk, 'setApiVersion'))->toBeTrue();
    expect(method_exists($sdk, 'getApiVersion'))->toBeTrue();
});

test('sdk can set and get api version', function () {
    $sdk = new V2BoardSDK();
    
    expect($sdk->getApiVersion())->toBe('v1');
    
    $sdk->setApiVersion('v2');
    expect($sdk->getApiVersion())->toBe('v2');
    
    $sdk->setApiVersion('v3');
    expect($sdk->getApiVersion())->toBe('v3');
});

test('sdk can be initialized with custom api version', function () {
    $sdk = new V2BoardSDK('https://example.com', [
        'api_version' => 'v2'
    ]);
    
    expect($sdk->getApiVersion())->toBe('v2');
});

test('sdk auth has cache methods', function () {
    $sdk = new V2BoardSDK();
    
    expect(method_exists($sdk->auth(), 'setCacheEnabled'))->toBeTrue();
    expect(method_exists($sdk->auth(), 'isCacheEnabled'))->toBeTrue();
    expect(method_exists($sdk->auth(), 'setCacheTtl'))->toBeTrue();
    expect(method_exists($sdk->auth(), 'getCacheTtl'))->toBeTrue();
    expect(method_exists($sdk->auth(), 'setCacheFile'))->toBeTrue();
    expect(method_exists($sdk->auth(), 'getCacheFile'))->toBeTrue();
    expect(method_exists($sdk->auth(), 'setCacheDriver'))->toBeTrue();
});

test('sdk can enable cache through auth', function () {
    $sdk = new V2BoardSDK();
    
    $sdk->auth()->setCacheEnabled(true);
    expect($sdk->auth()->isCacheEnabled())->toBeTrue();
});

test('sdk can set cache TTL through auth', function () {
    $sdk = new V2BoardSDK();
    
    $sdk->auth()->setCacheTtl(1800);
    expect($sdk->auth()->getCacheTtl())->toBe(1800);
});

test('sdk can set cache file through auth', function () {
    $sdk = new V2BoardSDK();
    
    $customPath = '/tmp/custom_cache.json';
    $sdk->auth()->setCacheFile($customPath);
    expect($sdk->auth()->getCacheFile())->toBe($customPath);
});

test('sdk can set cache driver through auth', function () {
    $sdk = new V2BoardSDK();
    
    $driver = function() { return 'test'; };
    $sdk->auth()->setCacheDriver($driver);
    
    expect($sdk->auth())->toHaveProperty('cacheDriver');
}); 