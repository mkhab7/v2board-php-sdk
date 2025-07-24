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