<?php

require_once 'vendor/autoload.php';

use Mkhab7\V2Board\SDK\V2BoardSDK;

// Initialize SDK with your V2Board instance URL
$sdk = new V2BoardSDK('https://sr3.x-upload.org');

try {
    // Login with your credentials
    $response = $sdk->login('matinahmadi@gmail.com', '4e7ef213befe46b7866d4ba33e971cf4');
    
    echo "=== Login Information ===\n";
    echo "Login successful!\n";
    echo "Token: " . $sdk->getToken() . "\n";
    echo "Is Admin: " . ($sdk->isAdmin() ? 'Yes' : 'No') . "\n";
    echo "Auth Data: " . $sdk->getAuthData() . "\n";
    
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
            
            echo "Online Users: " . $sdk->admin()->getOnlineUsers() . "\n";
            echo "Month Income: " . $sdk->admin()->getMonthIncome() . "\n";
            echo "Month Register Total: " . $sdk->admin()->getMonthRegisterTotal() . "\n";
            echo "Day Register Total: " . $sdk->admin()->getDayRegisterTotal() . "\n";
            echo "Ticket Pending Total: " . $sdk->admin()->getTicketPendingTotal() . "\n";
            echo "Commission Pending Total: " . $sdk->admin()->getCommissionPendingTotal() . "\n";
            echo "Day Income: " . $sdk->admin()->getDayIncome() . "\n";
            echo "Last Month Income: " . $sdk->admin()->getLastMonthIncome() . "\n";
            
            echo "\n=== Admin User Management ===\n";
            
            echo "Generating new user...\n";
            try {
                $userData = [
                    'email_prefix' => 'testuser',
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
                'id' => '1',
                'invite_user_id' => '',
                'telegram_id' => '',
                'email' => 'matinahmadi@gmail.com',
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
            $updateResult = $sdk->updateUser($updateData);
            echo "Update Result: " . json_encode($updateResult) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    if ($e instanceof \Mkhab7\V2Board\SDK\Exceptions\AuthenticationException) {
        echo "Authentication failed. Please check your credentials.\n";
    }
} 