<?php
// Quick test - copy this entire content to a new file called "test.php" on your server
echo "<h1>Pharmacy-X Quick Test</h1>";
echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_NAME'] . "</p>";

// Test if original config loads
if (file_exists('db_Config/config.php')) {
    echo "<p>✅ Config file exists</p>";
} else {
    echo "<p>❌ Config file missing</p>";
}

// Test session
session_start();
$_SESSION['test'] = 'Working';
echo "<p>✅ Session test: " . $_SESSION['test'] . "</p>";

echo "<p><a href='index.php'>→ Try Main Page</a></p>";
echo "<p><a href='signin.php'>→ Try Login Page</a></p>";
?>
