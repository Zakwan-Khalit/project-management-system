<?php
// Debug the actual dashboard response
$baseUrl = 'http://localhost:8080';

// Initialize curl session for cookie management
$cookieJar = tempnam('/tmp', 'cookies');

function makeCurlRequest($url, $postData = null, $cookieJar = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false); // Get body only
    
    if ($cookieJar) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
    }
    
    if ($postData) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['response' => $response, 'code' => $httpCode];
}

echo "Getting login page...\n";
$loginPageResponse = makeCurlRequest("$baseUrl/login", null, $cookieJar);

// Try to extract CSRF token if it exists
$csrfToken = '';
if (preg_match('/name="csrf_test_name"\s+value="([^"]+)"/', $loginPageResponse['response'], $matches)) {
    $csrfToken = $matches[1];
}

echo "Logging in...\n";
$loginData = [
    'email' => 'admin@projectmanagement.com',
    'password' => 'admin123'
];

if ($csrfToken) {
    $loginData['csrf_test_name'] = $csrfToken;
}

$loginResponse = makeCurlRequest("$baseUrl/login", $loginData, $cookieJar);

echo "Getting dashboard...\n";
$dashboardResponse = makeCurlRequest("$baseUrl/dashboard", null, $cookieJar);

// Save the response to a file to examine
file_put_contents('dashboard_response.html', $dashboardResponse['response']);

echo "Dashboard response saved to dashboard_response.html\n";
echo "Response length: " . strlen($dashboardResponse['response']) . " characters\n";
echo "First 500 characters:\n";
echo substr($dashboardResponse['response'], 0, 500) . "\n";

// Clean up
unlink($cookieJar);
