<?php
// Health check endpoint for DigitalOcean
// This endpoint ensures the application is running properly

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Start session to test session functionality
session_start();

// Check if session storage is working
if (!isset($_SESSION['health_check_time'])) {
    $_SESSION['health_check_time'] = time();
}

// Include configuration
try {
    require_once './db_Config/config.php';
    $configLoaded = true;
} catch (Exception $e) {
    $configLoaded = false;
    error_log("Health check: Config load failed - " . $e->getMessage());
}

// Check session storage
$sessionWorking = false;
if (isset($_SESSION['users']) || isset($_SESSION['pharmacy_data'])) {
    $sessionWorking = true;
} else {
    // Try to initialize session storage
    try {
        require_once './db_Config/session_config.php';
        $sessionWorking = true;
    } catch (Exception $e) {
        error_log("Health check: Session init failed - " . $e->getMessage());
    }
}

// Check file permissions
$filesWritable = is_writable('./Images/') && is_writable('./');

// Check PHP version
$phpVersion = phpversion();
$phpOk = version_compare($phpVersion, '7.4.0', '>=');

// Overall health status
$healthy = $configLoaded && $sessionWorking && $filesWritable && $phpOk;

// Response
$response = [
    'status' => $healthy ? 'healthy' : 'unhealthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => [
        'configuration' => $configLoaded ? 'ok' : 'fail',
        'session_storage' => $sessionWorking ? 'ok' : 'fail',
        'file_permissions' => $filesWritable ? 'ok' : 'fail',
        'php_version' => $phpOk ? 'ok' : 'fail'
    ],
    'details' => [
        'php_version' => $phpVersion,
        'session_id' => session_id(),
        'memory_usage' => memory_get_usage(true),
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size')
    ]
];

// Set appropriate HTTP status code
if ($healthy) {
    http_response_code(200);
} else {
    http_response_code(503); // Service Unavailable
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT);

// Log health check
error_log("Health check: " . ($healthy ? 'HEALTHY' : 'UNHEALTHY') . " - " . json_encode($response['checks']));
?>
