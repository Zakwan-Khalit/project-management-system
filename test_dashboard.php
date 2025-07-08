<?php
// Simple test to check if dashboard URL redirects properly
$url = 'http://localhost:8080/dashboard';

echo "Testing dashboard URL: $url\n";

// Use curl to check the response
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
echo "Final URL: $finalUrl\n";

if ($httpCode == 200) {
    echo "✅ Dashboard is accessible!\n";
    if (strpos($response, '<nav class="sidebar"') !== false) {
        echo "✅ Sidebar found in response - template is working!\n";
    } else {
        echo "❌ Sidebar not found - template might not be working.\n";
    }
    
    if (strpos($response, '<main class="main-content">') !== false) {
        echo "✅ Main content area found in response!\n";
    } else {
        echo "❌ Main content area not found.\n";
    }
} elseif ($httpCode == 302 || $httpCode == 301) {
    echo "⚠️  Dashboard redirected (probably to login) - need to authenticate first.\n";
} else {
    echo "❌ Error accessing dashboard. HTTP Code: $httpCode\n";
}
