<?php
// Test to see what's happening with requests
echo "Testing asset routing...\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current working directory: " . getcwd() . "\n";
echo "File exists check:\n";

$asset_path = $_SERVER['DOCUMENT_ROOT'] . '/project-management-system/public/assets/js/jquery/jquery.min.js';
echo "Looking for: $asset_path\n";
echo "File exists: " . (file_exists($asset_path) ? 'YES' : 'NO') . "\n";

if (file_exists($asset_path)) {
    echo "File size: " . filesize($asset_path) . " bytes\n";
}

// Check .htaccess
$htaccess_path = $_SERVER['DOCUMENT_ROOT'] . '/project-management-system/.htaccess';
echo "\n.htaccess exists: " . (file_exists($htaccess_path) ? 'YES' : 'NO') . "\n";
if (file_exists($htaccess_path)) {
    echo ".htaccess content:\n";
    echo file_get_contents($htaccess_path);
}
?>
