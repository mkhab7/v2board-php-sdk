# V2Board SDK

A powerful PHP SDK for V2Board API that makes it easy to interact with your V2Board instance.

## Installation

### Using Composer

```bash
composer require mkhab7/v2board-sdk
```

### Manual Installation

1. Clone the repository:
```bash
git clone https://github.com/mkhab7/v2board-php-sdk.git
cd v2board-php-sdk
```

2. Install dependencies:
```bash
composer install
```

3. Include the autoloader:
```php
require_once 'vendor/autoload.php';
```

## Features

- 🔐 **Authentication** - Login, logout, and token management
- 👤 **User Management** - Get user info, generate users, update users
- 📊 **System Monitoring** - Real-time system statistics and performance data
- 📋 **Plan Management** - Fetch, create, and update subscription plans
- 🖥️ **Server Groups** - Manage server groups and their configurations
- 👥 **Admin Tools** - Advanced user search by email, ID, or token
- 🌐 **HTTP Client** - Built-in HTTP client for custom API calls
- 🧪 **Testing** - Comprehensive test suite with Pest framework

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use Mkhab7\V2Board\SDK\V2BoardSDK;

// Initialize SDK with API version
$sdk = new V2BoardSDK('https://your-v2board-instance.com', [
    'api_version' => 'v1' // Change to 'v2', 'v3', etc. as needed
]);

// Login
try {
    $response = $sdk->login('your-email@example.com', 'your-password');
    echo "Login successful! Token: " . $sdk->getToken();
    echo "API Version: " . $sdk->getApiVersion();
} catch (Exception $e) {
    echo "Login failed: " . $e->getMessage();
}
```

## Authentication

```php
// Login with credentials
$response = $sdk->login($email, $password);

// Check if user is logged in
if ($sdk->isAuthenticated()) {
    echo "User is logged in";
}

// Get current token
$token = $sdk->getToken();

// Check if user is admin
if ($sdk->isAdmin()) {
    echo "User has admin privileges";
}

// Get JWT auth data
$authData = $sdk->getAuthData();

// Change API version
$sdk->setApiVersion('v2');

// Get current API version
$version = $sdk->getApiVersion();

// Logout
$sdk->logout();
```

## Authentication Caching

The SDK supports caching authentication tokens to avoid repeated login requests:

```php
// Enable caching (optional)
$sdk->auth()->setCacheEnabled(true);

// Set cache TTL in seconds (default: 3600 = 1 hour)
$sdk->auth()->setCacheTtl(1800); // 30 minutes

// Set custom cache file location
$sdk->auth()->setCacheFile('/path/to/custom/cache.json');

// Use external cache driver (e.g., Laravel Cache)
$sdk->auth()->setCacheDriver(function($action, $key, $value = null, $ttl = null) {
    if ($action === 'get') {
        return cache()->get($key);
    } elseif ($action === 'put') {
        return cache()->put($key, $value, $ttl);
    } elseif ($action === 'forget') {
        return cache()->forget($key);
    }
});

// With caching enabled, subsequent login() calls will use cached data
// if it hasn't expired, avoiding unnecessary API requests
```

## User Information

```php
// Get user email
$email = $sdk->user()->getEmail();

// Get transfer limit in GB
$transferGB = $sdk->user()->getTransferEnableGB();

// Get account balance
$balance = $sdk->user()->getBalance();

// Get current plan ID
$planId = $sdk->user()->getPlanId();

// Check if account is banned
if ($sdk->user()->isBanned()) {
    echo "Account is suspended";
}

// Get user UUID
$uuid = $sdk->user()->getUuid();

// Get avatar URL
$avatarUrl = $sdk->user()->getAvatarUrl();
```

## System Statistics

```php
// Get system status
$status = $sdk->stats()->getStatus();

// Get number of running processes
$processes = $sdk->stats()->getProcesses();

// Get failed job count
$failedJobs = $sdk->stats()->getFailedJobs();

// Get recent job count
$recentJobs = $sdk->stats()->getRecentJobs();

// Get jobs per minute
$jobsPerMinute = $sdk->stats()->getJobsPerMinute();
```

## Admin Operations

```php
// Set node ID for admin operations
$sdk->setNodeId('your-node-id');

// Get admin statistics
$onlineUsers = $sdk->admin()->getOnlineUsers();
$monthIncome = $sdk->admin()->getMonthIncome();
$monthRegisterTotal = $sdk->admin()->getMonthRegisterTotal();

// Search users by email
$user = $sdk->admin()->fetchUserByEmail('user@example.com');

// Search users by ID
$user = $sdk->admin()->fetchUserById(10);

// Search users by token
$user = $sdk->admin()->fetchUserByToken('user_token_here');

// Generate new user
$userData = [
    'email_prefix' => 'newuser',
    'email_suffix' => 'gmail.com',
    'password' => '123456',
    'expired_at' => '1752125627',
    'plan_id' => '2'
];
$result = $sdk->generateUser($userData);

// Update existing user
$updateData = [
    'id' => '1',
    'email' => 'user@example.com',
    'balance' => '100',
    'plan_id' => '3',
    'transfer_enable' => '1073741824',
    'banned' => '0'
];
$result = $sdk->updateUser($updateData);
```

## Plan Management

```php
// Get all plans
$plans = $sdk->plan()->fetch();

// Find plan by ID
$plan = $sdk->plan()->getPlanById(1);

// Find plan by name
$plan = $sdk->plan()->getPlanByName('25 GB');

// Get plans for specific group
$plans = $sdk->plan()->getPlansByGroupId(1);

// Get only visible plans
$visiblePlans = $sdk->plan()->getVisiblePlans();

// Get total plan count
$planCount = $sdk->plan()->getPlanCount();

// Get total users across all plans
$totalUsers = $sdk->plan()->getTotalUserCount();

// Create or update plan
$planData = [
    'id' => '1',
    'group_id' => '1',
    'transfer_enable' => '.5',
    'name' => '1 GB',
    'show' => '1',
    'sort' => '1',
    'renew' => '1'
];
$result = $sdk->plan()->save($planData);
```

## Server Group Management

```php
// Get all server groups
$groups = $sdk->serverGroup()->fetch();

// Find group by ID
$group = $sdk->serverGroup()->getGroupById(1);

// Find group by name
$group = $sdk->serverGroup()->getGroupByName('normal');

// Get total group count
$groupCount = $sdk->serverGroup()->getGroupCount();

// Get total users across all groups
$totalUsers = $sdk->serverGroup()->getTotalUserCount();

// Get total servers across all groups
$totalServers = $sdk->serverGroup()->getTotalServerCount();
```

## HTTP Client

```php
// Make custom GET request
$response = $sdk->http()->get('/api/v1/custom/endpoint');

// Make custom POST request
$response = $sdk->http()->post('/api/v1/custom/endpoint', [
    'key' => 'value'
]);
```

## Error Handling

```php
try {
    $sdk->login($email, $password);
} catch (\Mkhab7\V2Board\SDK\Exceptions\AuthenticationException $e) {
    // Handle authentication errors
    echo "Login failed: " . $e->getMessage();
} catch (\Mkhab7\V2Board\SDK\Exceptions\HttpException $e) {
    // Handle network errors
    echo "Network error: " . $e->getMessage();
} catch (\Mkhab7\V2Board\SDK\Exceptions\V2BoardException $e) {
    // Handle general SDK errors
    echo "SDK error: " . $e->getMessage();
}
```

## Testing

Run the test suite:

```bash
./vendor/bin/pest
```

## License

MIT License - see LICENSE file for details. 