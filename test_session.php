<?php
// Test script to debug session issues
require_once 'vendor/autoload.php';

use CodeIgniter\Session\Session;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\URI;

// Mock a simple request to initialize CodeIgniter
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/test';

// Load CodeIgniter bootstrap
$app = require_once FCPATH . '../app/Config/Boot/production.php';

// Get session instance
$session = session();

echo "=== Session Test Debug ===\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Started: " . (session_status() == PHP_SESSION_ACTIVE ? 'Yes' : 'No') . "\n";
echo "Session Save Path: " . session_save_path() . "\n";
echo "Session Name: " . session_name() . "\n";

echo "\n=== Session Data ===\n";
$sessionData = $session->get();
if (empty($sessionData)) {
    echo "Session is empty\n";
} else {
    print_r($sessionData);
}

// Check specific keys
echo "\n=== User Session Data ===\n";
echo "user_id: " . ($session->get('user_id') ?: 'not set') . "\n";
echo "logged_in: " . ($session->get('logged_in') ? 'true' : 'false') . "\n";
echo "user_data: " . print_r($session->get('user_data'), true) . "\n";

// Set a test value
$session->set('test_key', 'test_value_' . time());
echo "\n=== After Setting Test Value ===\n";
echo "test_key: " . $session->get('test_key') . "\n";

// Check if session files are being written
$sessionDir = WRITEPATH . 'session';
echo "\n=== Session Directory ===\n";
echo "Directory: $sessionDir\n";
echo "Writable: " . (is_writable($sessionDir) ? 'Yes' : 'No') . "\n";

// List recent session files
$files = glob($sessionDir . '/ci_session*');
if ($files) {
    echo "Recent session files:\n";
    usort($files, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    
    $recent = array_slice($files, 0, 3);
    foreach ($recent as $file) {
        $content = file_get_contents($file);
        echo "  " . basename($file) . " (" . filemtime($file) . "): " . strlen($content) . " bytes\n";
        echo "    Content: " . substr($content, 0, 100) . "\n";
    }
}

echo "\n=== PHP Session Info ===\n";
echo "PHP Session ID: " . session_id() . "\n";
echo "PHP Session Status: " . session_status() . "\n";
echo "PHP Session Module: " . ini_get('session.save_handler') . "\n";
echo "PHP Session Path: " . ini_get('session.save_path') . "\n";
