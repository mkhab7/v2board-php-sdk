# V2Board SDK

A modern PHP SDK for V2Board API with clean architecture and SOLID principles.

## Features

- 🔐 Authentication with token management
- 🌐 HTTP client with Guzzle integration
- 🛡️ Proper error handling and exceptions
- 📦 PSR-4 autoloading
- 🧪 SOLID principles and design patterns
- ⚡ Easy to use and extend
- 🧪 Pest testing framework
- 👥 User management (generate, update)
- 📊 System statistics
- 👤 User information

## Installation

```bash
composer require mkhab7/v2board-sdk
```

Or add to your `composer.json`:

```json
{
    "require": {
        "mkhab7/v2board-sdk": "^1.0"
    }
}
```

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use Mkhab7\V2Board\SDK\V2BoardSDK;

// Initialize SDK
$sdk = new V2BoardSDK('https://your-v2board-instance.com');

// Login
try {
    $response = $sdk->login('your-email@example.com', 'your-password');
    echo "Token: " . $sdk->getToken();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Configuration

```php
$sdk = new V2BoardSDK('https://your-v2board-instance.com', [
    'timeout' => 30,
    'verify_ssl' => true,
    'headers' => [
        'User-Agent' => 'MyApp/1.0'
    ]
]);
```

## API Reference

### Authentication

```php
// Login
$response = $sdk->login($email, $password);

// Check authentication status
if ($sdk->isAuthenticated()) {
    echo "User is logged in";
}

// Get current token
$token = $sdk->getToken();

// Check if admin
if ($sdk->isAdmin()) {
    echo "User is admin";
}

// Get auth data (JWT)
$authData = $sdk->getAuthData();

// Logout
$sdk->logout();
```

### User Information

```php
// Get user email
$email = $sdk->user()->getEmail();

// Get transfer limit in GB
$transferGB = $sdk->user()->getTransferEnableGB();

// Get balance
$balance = $sdk->user()->getBalance();

// Get plan ID
$planId = $sdk->user()->getPlanId();

// Check if banned
if ($sdk->user()->isBanned()) {
    echo "User is banned";
}

// Get UUID
$uuid = $sdk->user()->getUuid();
```

### System Statistics

```php
// Get system status
$status = $sdk->stats()->getStatus();

// Get processes count
$processes = $sdk->stats()->getProcesses();

// Get failed jobs
$failedJobs = $sdk->stats()->getFailedJobs();

// Get recent jobs
$recentJobs = $sdk->stats()->getRecentJobs();
```

### Admin Operations

```php
// Set node ID for admin operations
$sdk->setNodeId('your-node-id');

// Get admin statistics
$onlineUsers = $sdk->admin()->getOnlineUsers();
$monthIncome = $sdk->admin()->getMonthIncome();
$monthRegisterTotal = $sdk->admin()->getMonthRegisterTotal();

// Generate new user
$userData = [
    'email_prefix' => 'test',
    'email_suffix' => 'gmail.com',
    'password' => '123456',
    'expired_at' => '1752125627',
    'plan_id' => '2'
];
$result = $sdk->generateUser($userData);

// Update user
$updateData = [
    'id' => '1',
    'email' => 'user@example.com',
    'balance' => '100',
    'plan_id' => '3',
    // ... other fields
];
$result = $sdk->updateUser($updateData);
```

### HTTP Client

```php
// Make GET request
$response = $sdk->http()->get('/api/v1/user/profile');

// Make POST request
$response = $sdk->http()->post('/api/v1/user/update', [
    'name' => 'John Doe'
]);
```

## Architecture

The SDK follows SOLID principles and uses several design patterns:

- **Dependency Injection**: All dependencies are injected through constructors
- **Interface Segregation**: Separate interfaces for different responsibilities
- **Single Responsibility**: Each class has a single, well-defined purpose
- **Strategy Pattern**: HTTP client can be easily swapped
- **Factory Pattern**: Configuration objects are created with sensible defaults

### Directory Structure

```
src/
├── Api/
│   ├── Admin.php
│   ├── Stats.php
│   └── User.php
├── Auth/
│   └── Authentication.php
├── Config/
│   └── Config.php
├── Contracts/
│   ├── AuthInterface.php
│   └── HttpClientInterface.php
├── Exceptions/
│   ├── AuthenticationException.php
│   ├── HttpException.php
│   └── V2BoardException.php
├── Http/
│   └── GuzzleHttpClient.php
└── V2BoardSDK.php
```

## Testing

This SDK uses Pest for testing. To run tests:

```bash
./vendor/bin/pest
```

Or run specific test suites:

```bash
./vendor/bin/pest tests/Unit
./vendor/bin/pest tests/Feature
```

## Error Handling

The SDK provides specific exception classes:

```php
try {
    $sdk->login($email, $password);
} catch (\Mkhab7\V2Board\SDK\Exceptions\AuthenticationException $e) {
    // Handle authentication errors
} catch (\Mkhab7\V2Board\SDK\Exceptions\HttpException $e) {
    // Handle network errors
} catch (\Mkhab7\V2Board\SDK\Exceptions\V2BoardException $e) {
    // Handle general SDK errors
}
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

MIT License - see LICENSE file for details. 