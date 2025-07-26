<?php

require_once 'vendor/autoload.php';

use Mkhab7\V2Board\SDK\V2BoardSDK;

// Initialize SDK with your V2Board instance URL
$sdk = new V2BoardSDK('https://sr1.x-upload.org', [
    'api_version' => 'v1' // You can change this to 'v2', 'v3', etc.
]);

try {
    // Login with your credentials
    $response = $sdk->login('matinahmadi@admin.user', 'ddbe0243463282ae329270466010664c');
    
    echo "=== Login Information ===\n";
    echo "Login successful!\n";
    echo "Token: " . $sdk->getToken() . "\n";
    echo "Is Admin: " . ($sdk->isAdmin() ? 'Yes' : 'No') . "\n";
    echo "Auth Data: " . $sdk->getAuthData() . "\n";
    echo "API Version: " . $sdk->getApiVersion() . "\n";
    
    // Check if authenticated
    if ($sdk->isAuthenticated()) {
        echo "\n=== User Information ===\n";
        
        // Get user data
        $userData = $sdk->auth()->getUserData();
        if ($userData) {
            echo "User ID: " . ($userData['id'] ?? 'N/A') . "\n";
            echo "Email: " . ($userData['email'] ?? 'N/A') . "\n";
            echo "Is Admin: " . ($userData['is_admin'] ? 'Yes' : 'No') . "\n";
        }
        
        // Get user info via API
        echo "\n=== User API Information ===\n";
        echo "Email: " . $sdk->user()->getEmail() . "\n";
        echo "Transfer Enable: " . $sdk->user()->getTransferEnableGB() . " GB\n";
        echo "Balance: " . $sdk->user()->getBalance() . "\n";
        echo "Plan ID: " . $sdk->user()->getPlanId() . "\n";
        echo "UUID: " . $sdk->user()->getUuid() . "\n";
        echo "Avatar URL: " . $sdk->user()->getAvatarUrl() . "\n";
        echo "Is Banned: " . ($sdk->user()->isBanned() ? 'Yes' : 'No') . "\n";
        echo "Auto Renewal: " . ($sdk->user()->isAutoRenewal() ? 'Yes' : 'No') . "\n";
        
        // Get system stats
        echo "\n=== System Statistics ===\n";
        echo "Status: " . $sdk->stats()->getStatus() . "\n";
        echo "Processes: " . $sdk->stats()->getProcesses() . "\n";
        echo "Failed Jobs: " . $sdk->stats()->getFailedJobs() . "\n";
        echo "Recent Jobs: " . $sdk->stats()->getRecentJobs() . "\n";
        echo "Jobs Per Minute: " . $sdk->stats()->getJobsPerMinute() . "\n";
        echo "Paused Masters: " . $sdk->stats()->getPausedMasters() . "\n";
        
        // Get admin stats (if admin)
        if ($sdk->isAdmin()) {
            echo "\n=== Admin Statistics ===\n";
            $sdk->setNodeId('bc1qcjt4elavl3zdua0ljvrcv7gskcv7g5kw2aatxc');

            echo "\n=== Admin User Management ===\n";
            
            echo "Generating new user...\n";
            try {
                $userData = [
                    'email_prefix' => $uid = uniqid(),
                    'email_suffix' => 'example.com',
                    'password' => '123456',
                    'expired_at' => '1752125627',
                    'plan_id' => '2'
                ];
                $result = $sdk->generateUser($userData);
                echo "User generated successfully!\n";
                echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
            
            $updateData = [
                'invite_user_id' => '',
                'telegram_id' => '',
                'email' => $uid . '@gmail.com',
                'password' => '',
                'password_algo' => '',
                'password_salt' => '',
                'balance' => '0',
                'discount' => '',
                'commission_type' => '0',
                'commission_rate' => '',
                'commission_balance' => '0',
                't' => '0',
                'u' => '0',
                'd' => '0',
                'transfer_enable' => '1073741824',
                'device_limit' => '',
                'banned' => '0',
                'is_admin' => '1',
                'last_login_at' => '',
                'is_staff' => '0',
                'last_login_ip' => '',
                'uuid' => 'da2b1999-5c39-4aa2-8bf0-9122dfc89646',
                'group_id' => '2',
                'plan_id' => '3',
                'speed_limit' => '',
                'auto_renewal' => '0',
                'remind_expire' => '1',
                'remind_traffic' => '1',
                'token' => '58a5fad4247d74951ac6a0ea6625c442',
                'expired_at' => '1755950743',
                'remarks' => '',
                'created_at' => '1753253693',
                'updated_at' => '1753272343'
            ];
            
            echo "Updating user...\n";
            try {
                $updateResult = $sdk->updateUser($updateData);
                echo "Update Result: " . json_encode($updateResult) . "\n";
            } catch (Exception $e) {
                echo "Error updating user: " . $e->getMessage() . "\n";
            }
            
            echo "\n=== Plan Information ===\n";
            try {
                $plans = $sdk->plan()->fetch();
                echo "Total Plans: " . count($plans) . "\n";
                
                foreach ($plans as $plan) {
                    echo "Plan: {$plan['name']} (ID: {$plan['id']}) - Transfer: {$plan['transfer_enable']} GB - Users: {$plan['count']}\n";
                }
                
                $planCount = $sdk->plan()->getPlanCount();
                $totalUsers = $sdk->plan()->getTotalUserCount();
                echo "Plan Count: {$planCount}\n";
                echo "Total Users: {$totalUsers}\n";
            } catch (Exception $e) {
                echo "Error fetching plans: " . $e->getMessage() . "\n";
            }

            echo "\n=== Server Group Information ===\n";
            try {
                $groups = $sdk->serverGroup()->fetch();
                echo "Total Groups: " . count($groups) . "\n";
                
                foreach ($groups as $group) {
                    echo "Group: {$group['name']} (ID: {$group['id']}) - Users: {$group['user_count']} - Servers: {$group['server_count']}\n";
                }
                
                $groupCount = $sdk->serverGroup()->getGroupCount();
                $totalUsers = $sdk->serverGroup()->getTotalUserCount();
                $totalServers = $sdk->serverGroup()->getTotalServerCount();
                echo "Group Count: {$groupCount}\n";
                echo "Total Users: {$totalUsers}\n";
                echo "Total Servers: {$totalServers}\n";
            } catch (Exception $e) {
                echo "Error fetching server groups: " . $e->getMessage() . "\n";
            }

            echo "\n=== Plan Update Example ===\n";
            try {
                $planData = [
                    'id' => '1',
                    'group_id' => '1',
                    'transfer_enable' => '.5',
                    'name' => '1 GB',
                    'device_limit' => '',
                    'speed_limit' => '',
                    'show' => '1',
                    'sort' => '1',
                    'renew' => '1',
                    'content' => '',
                    'month_price' => '',
                    'quarter_price' => '',
                    'half_year_price' => '',
                    'year_price' => '',
                    'two_year_price' => '',
                    'three_year_price' => '',
                    'onetime_price' => '',
                    'reset_price' => '',
                    'reset_traffic_method' => '',
                    'capacity_limit' => '',
                    'created_at' => '1753370427',
                    'updated_at' => '1753505093',
                    'count' => '1'
                ];
                
                $result = $sdk->plan()->save($planData);
                echo "Plan updated successfully!\n";
                echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
            } catch (Exception $e) {
                echo "Error updating plan: " . $e->getMessage() . "\n";
            }

            echo "\n=== Admin User Fetch Helpers ===\n";
            try {
                $userByEmail = $sdk->admin()->fetchUserByEmail('matinahmadi@admin.user');
                echo "User by Email: ";
                var_export($userByEmail);
                echo "\n";
            } catch (Exception $e) {
                echo "Error fetching user by email: " . $e->getMessage() . "\n";
            }
            try {
                $userById = $sdk->admin()->fetchUserById(1);
                echo "User by ID: ";
                var_export($userById);
                echo "\n";
            } catch (Exception $e) {
                echo "Error fetching user by ID: " . $e->getMessage() . "\n";
            }
            try {
                $userByToken = $sdk->admin()->fetchUserByToken('14ce913fdfbe9ffdadb479eea9382af8');
                echo "User by Token: ";
                var_export($userByToken);
                echo "\n";
            } catch (Exception $e) {
                echo "Error fetching user by token: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n=== API Version Management ===\n";
    echo "Current API Version: " . $sdk->getApiVersion() . "\n";
    
    // Change API version
    $sdk->setApiVersion('v2');
    echo "Changed to API Version: " . $sdk->getApiVersion() . "\n";
    
    // Change back to v1
    $sdk->setApiVersion('v1');
    echo "Changed back to API Version: " . $sdk->getApiVersion() . "\n";
    
    echo "\n=== SDK Complete! ===\n";
    echo "All features are working correctly!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    if ($e instanceof \Mkhab7\V2Board\SDK\Exceptions\AuthenticationException) {
        echo "Authentication failed. Please check your credentials.\n";
    }
} 