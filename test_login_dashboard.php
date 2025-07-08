<?php
// Simple test script to check login and dashboard
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Login and Dashboard</h1>";

// Test login
echo "<h2>1. Testing Login</h2>";
$loginUrl = "http://localhost:8080/login";
$loginData = [
    'email' => 'admin@projectmanagement.local',
    'password' => 'admin123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'X-Requested-With: XMLHttpRequest'
]);

$loginResponse = curl_exec($ch);
$loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p><strong>Login HTTP Code:</strong> $loginHttpCode</p>";

// Extract body from response
$bodyStart = strpos($loginResponse, "\r\n\r\n");
if ($bodyStart !== false) {
    $loginBody = substr($loginResponse, $bodyStart + 4);
    echo "<p><strong>Login Response:</strong> " . htmlspecialchars($loginBody) . "</p>";
    
    $loginJson = json_decode($loginBody, true);
    if ($loginJson && isset($loginJson['success']) && $loginJson['success']) {
        echo "<p style='color: green;'>✓ Login successful!</p>";
        
        // Test dashboard access
        echo "<h2>2. Testing Dashboard Access</h2>";
        $dashboardUrl = "http://localhost:8080/dashboard";
        
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $dashboardUrl);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HEADER, true);
        curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookies.txt');
        
        $dashboardResponse = curl_exec($ch2);
        $dashboardHttpCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);
        
        echo "<p><strong>Dashboard HTTP Code:</strong> $dashboardHttpCode</p>";
        
        // Extract body from dashboard response
        $bodyStart = strpos($dashboardResponse, "\r\n\r\n");
        if ($bodyStart !== false) {
            $dashboardBody = substr($dashboardResponse, $bodyStart + 4);
            
            // Check if it contains main template elements
            if (strpos($dashboardBody, 'sidebar') !== false) {
                echo "<p style='color: green;'>✓ Dashboard contains sidebar!</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Dashboard does not contain sidebar</p>";
            }
            
            if (strpos($dashboardBody, 'main-content') !== false) {
                echo "<p style='color: green;'>✓ Dashboard contains main-content!</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Dashboard does not contain main-content</p>";
            }
            
            if (strpos($dashboardBody, 'Dashboard') !== false) {
                echo "<p style='color: green;'>✓ Dashboard contains Dashboard title!</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Dashboard does not contain Dashboard title</p>";
            }
            
            // Show first 1000 characters
            echo "<h3>Dashboard Response Preview:</h3>";
            echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow: auto;'>";
            echo htmlspecialchars(substr($dashboardBody, 0, 1000));
            if (strlen($dashboardBody) > 1000) {
                echo "\n\n... (truncated, total length: " . strlen($dashboardBody) . " characters)";
            }
            echo "</pre>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Login failed!</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Could not parse login response</p>";
}

// Clean up
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}
?>
