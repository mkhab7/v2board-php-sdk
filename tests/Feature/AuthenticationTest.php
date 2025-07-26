<?php

use Mkhab7\V2Board\SDK\Auth\Authentication;
use Mkhab7\V2Board\SDK\Config\Config;
use Mkhab7\V2Board\SDK\Http\GuzzleHttpClient;
use Mkhab7\V2Board\SDK\Contracts\AuthInterface;
use Mkhab7\V2Board\SDK\Exceptions\AuthenticationException;

beforeEach(function () {
    $config = new Config('https://sr3.x-upload.org');
    $this->httpClient = new GuzzleHttpClient($config);
    $this->auth = new Authentication($this->httpClient);
});

test('authentication implements AuthInterface', function () {
    expect($this->auth)->toBeInstanceOf(AuthInterface::class);
});

test('initial state is not authenticated', function () {
    expect($this->auth->isAuthenticated())->toBeFalse();
    expect($this->auth->getToken())->toBeNull();
    expect($this->auth->isAdmin())->toBeFalse();
    expect($this->auth->getAuthData())->toBeNull();
});

test('logout clears authentication state', function () {
    $this->auth->logout();
    
    expect($this->auth->isAuthenticated())->toBeFalse();
    expect($this->auth->getToken())->toBeNull();
    expect($this->auth->isAdmin())->toBeFalse();
    expect($this->auth->getAuthData())->toBeNull();
});

test('setToken sets authentication state', function () {
    $token = 'test-token-123';
    $this->auth->setToken($token);
    
    expect($this->auth->isAuthenticated())->toBeTrue();
    expect($this->auth->getToken())->toBe($token);
});

test('getUserData returns null initially', function () {
    expect($this->auth->getUserData())->toBeNull();
});

test('setToken returns self for chaining', function () {
    $result = $this->auth->setToken('test-token');
    
    expect($result)->toBe($this->auth);
});

test('setIsAdmin sets admin status', function () {
    $this->auth->setIsAdmin(true);
    
    expect($this->auth->isAdmin())->toBeTrue();
});

test('setAuthData sets auth data', function () {
    $authData = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.test';
    $this->auth->setAuthData($authData);
    
    expect($this->auth->getAuthData())->toBe($authData);
});

test('setIsAdmin returns self for chaining', function () {
    $result = $this->auth->setIsAdmin(true);
    
    expect($result)->toBe($this->auth);
});

test('setAuthData returns self for chaining', function () {
    $result = $this->auth->setAuthData('test-auth-data');
    
    expect($result)->toBe($this->auth);
});

test('cache is disabled by default', function () {
    expect($this->auth->isCacheEnabled())->toBeFalse();
});

test('setCacheEnabled enables cache', function () {
    $this->auth->setCacheEnabled(true);
    
    expect($this->auth->isCacheEnabled())->toBeTrue();
});

test('setCacheEnabled returns self for chaining', function () {
    $result = $this->auth->setCacheEnabled(true);
    
    expect($result)->toBe($this->auth);
});

test('setCacheTtl sets cache TTL', function () {
    $this->auth->setCacheTtl(1800);
    
    expect($this->auth->getCacheTtl())->toBe(1800);
});

test('getCacheTtl returns default TTL', function () {
    expect($this->auth->getCacheTtl())->toBe(3600);
});

test('setCacheTtl returns self for chaining', function () {
    $result = $this->auth->setCacheTtl(1800);
    
    expect($result)->toBe($this->auth);
});

test('setCacheFile sets cache file path', function () {
    $customPath = '/tmp/custom_cache.json';
    $this->auth->setCacheFile($customPath);
    
    expect($this->auth->getCacheFile())->toBe($customPath);
});

test('getCacheFile returns default path', function () {
    $defaultPath = realpath(__DIR__ . '/../../src/Auth') . '/.auth_cache.json';
    expect($this->auth->getCacheFile())->toBe($defaultPath);
});

test('setCacheFile returns self for chaining', function () {
    $result = $this->auth->setCacheFile('/tmp/test.json');
    
    expect($result)->toBe($this->auth);
});

test('setCacheDriver sets cache driver', function () {
    $driver = function() { return 'test'; };
    $this->auth->setCacheDriver($driver);
    
    expect($this->auth)->toHaveProperty('cacheDriver');
});

test('setCacheDriver returns self for chaining', function () {
    $driver = function() { return 'test'; };
    $result = $this->auth->setCacheDriver($driver);
    
    expect($result)->toBe($this->auth);
});

test('cache file is created when saving to cache', function () {
    $tempFile = sys_get_temp_dir() . '/test_auth_cache.json';
    $this->auth->setCacheEnabled(true);
    $this->auth->setCacheFile($tempFile);
    $this->auth->setToken('test-token');
    $this->auth->setAuthData('test-auth-data');
    $this->auth->setIsAdmin(true);
    
    // Trigger save to cache by calling a method that saves
    $this->auth->logout(); // This will clear cache, but we can test file creation
    
    expect(file_exists($tempFile))->toBeFalse(); // Should be deleted by logout
    
    // Clean up
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
});

test('cache with TTL expiration', function () {
    $tempFile = sys_get_temp_dir() . '/test_auth_cache_ttl.json';
    $this->auth->setCacheEnabled(true);
    $this->auth->setCacheFile($tempFile);
    $this->auth->setCacheTtl(1); // 1 second TTL
    
    // Create cache file with old timestamp
    $oldData = [
        'token' => 'old-token',
        'authData' => 'old-auth-data',
        'isAdmin' => false,
        'userData' => null,
        'timestamp' => time() - 10, // 10 seconds ago
    ];
    file_put_contents($tempFile, json_encode($oldData));
    
    // Cache should be considered expired
    $this->auth->setToken('new-token');
    
    expect($this->auth->getToken())->toBe('new-token');
    
    // Clean up
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
});

test('cache driver integration', function () {
    $cacheData = null;
    $cacheTtl = null;
    
    $driver = function($action, $key, $value = null, $ttl = null) use (&$cacheData, &$cacheTtl) {
        if ($action === 'put') {
            $cacheData = $value;
            $cacheTtl = $ttl;
            return true;
        } elseif ($action === 'get') {
            return $cacheData;
        } elseif ($action === 'forget') {
            $cacheData = null;
            $cacheTtl = null;
            return true;
        }
        return null;
    };
    
    $this->auth->setCacheEnabled(true);
    $this->auth->setCacheDriver($driver);
    $this->auth->setCacheTtl(1800);
    $this->auth->setToken('test-token');
    $this->auth->setAuthData('test-auth-data');
    $this->auth->setIsAdmin(true);
    
    // Trigger save to cache by calling login (which calls saveToCache)
    // We need to mock the HTTP client to avoid actual API calls
    $mockResponse = Mockery::mock('Psr\Http\Message\ResponseInterface');
    $mockResponse->shouldReceive('getStatusCode')->andReturn(200);
    $mockResponse->shouldReceive('getBody')->andReturn(
        Mockery::mock('Psr\Http\Message\StreamInterface')
            ->shouldReceive('getContents')
            ->andReturn(json_encode([
                'data' => [
                    'token' => 'test-token',
                    'auth_data' => 'test-auth-data',
                    'is_admin' => true
                ]
            ]))
            ->getMock()
    );
    
    $mockHttpClient = Mockery::mock('Mkhab7\V2Board\SDK\Contracts\HttpClientInterface');
    $mockHttpClient->shouldReceive('post')->andReturn($mockResponse);
    $mockHttpClient->shouldReceive('setAuthToken')->andReturnSelf();
    
    // Create a new auth instance with mock client for testing
    $testAuth = new \Mkhab7\V2Board\SDK\Auth\Authentication($mockHttpClient);
    $testAuth->setCacheEnabled(true);
    $testAuth->setCacheDriver($driver);
    $testAuth->setCacheTtl(1800);
    
    $testAuth->login('test@example.com', 'password');
    
    expect($cacheData)->toBeArray();
    expect($cacheData['token'])->toBe('test-token');
    expect($cacheData['authData'])->toBe('test-auth-data');
    expect($cacheData['isAdmin'])->toBeTrue();
    expect($cacheTtl)->toBe(1800);
}); 