<?php

// Simple test script to verify database connection and user lookup
require_once 'vendor/autoload.php';

// Load CodeIgniter config
$app = \CodeIgniter\Config\Services::initialize();
$config = new \Config\Database();

// Test database connection
try {
    $db = \Config\Database::connect();
    echo "✓ Database connected successfully\n";
    
    // Test if users table exists
    $tables = $db->listTables();
    echo "Available tables: " . implode(', ', $tables) . "\n";
    
    if (in_array('users', $tables)) {
        echo "✓ Users table exists\n";
        
        // Check users in the table
        $users = $db->table('users')->get()->getResultArray();
        echo "Users found: " . count($users) . "\n";
        
        foreach ($users as $user) {
            echo "- ID: {$user['id']}, Email: {$user['email']}, Active: {$user['is_active']}\n";
        }
        
        // Test specific admin user
        $admin = $db->table('users')->where('email', 'admin@projectmanagement.local')->get()->getRowArray();
        if ($admin) {
            echo "✓ Admin user found: ID {$admin['id']}\n";
            echo "  Password hash: " . substr($admin['password'], 0, 20) . "...\n";
            
            // Test password verification
            $testPassword = 'admin123';
            if (password_verify($testPassword, $admin['password'])) {
                echo "✓ Password verification successful\n";
            } else {
                echo "✗ Password verification failed\n";
            }
            
            // Check user_profile table
            if (in_array('user_profile', $tables)) {
                $profile = $db->table('user_profile')->where('user_id', $admin['id'])->get()->getRowArray();
                if ($profile) {
                    echo "✓ User profile found: {$profile['first_name']} {$profile['last_name']}\n";
                } else {
                    echo "✗ No user profile found\n";
                }
            }
            
        } else {
            echo "✗ Admin user not found\n";
        }
        
    } else {
        echo "✗ Users table does not exist\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}
