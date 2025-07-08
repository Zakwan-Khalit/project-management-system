<?php
// Extract actual content from dashboard response
$content = file_get_contents('dashboard_response.html');

// Find where the HTML starts (after debug scripts)
$htmlStart = strpos($content, '<!DOCTYPE html>');
if ($htmlStart === false) {
    $htmlStart = strpos($content, '<html');
}

if ($htmlStart === false) {
    echo "No HTML content found!\n";
    echo "Full content:\n";
    echo $content;
} else {
    $actualHtml = substr($content, $htmlStart);
    
    // Save cleaned content
    file_put_contents('dashboard_clean.html', $actualHtml);
    
    echo "Found HTML content starting at position: $htmlStart\n";
    echo "Saved clean HTML to dashboard_clean.html\n";
    
    if (strpos($actualHtml, 'sidebar') !== false) {
        echo "✅ SIDEBAR FOUND in clean HTML!\n";
    } else {
        echo "❌ Sidebar not found in clean HTML\n";
    }
    
    if (strpos($actualHtml, 'main-content') !== false) {
        echo "✅ MAIN-CONTENT FOUND in clean HTML!\n";
    } else {
        echo "❌ Main-content not found in clean HTML\n";
    }
    
    if (strpos($actualHtml, 'Dashboard') !== false) {
        echo "✅ DASHBOARD CONTENT FOUND in clean HTML!\n";
    } else {
        echo "❌ Dashboard content not found in clean HTML\n";
    }
}
?>
