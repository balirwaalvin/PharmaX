<?php
// Pharmacy-X Configuration - Session Storage for Demo
// This uses PHP sessions instead of a database for presentation purposes

error_reporting(E_ALL);
ini_set('log_errors', 1);

// Use session storage instead of database
$useSessionStorage = true;

if ($useSessionStorage) {
    // Include session-based storage
    require_once __DIR__ . '/session_config.php';
    
    // Set compatibility variables
    $Server = "Session Storage";
    $Username = "demo";
    $Password = "";
    $Database = "Pharmacy_Session_DB";
    $Port = 0;
    
    error_log("Using session-based storage for demo");
} else {
    // Original database code (kept for reference)
    $isDigitalOcean = (isset($_ENV['DATABASE_URL']) || isset($_SERVER['HTTP_HOST']) || php_sapi_name() !== 'cli');
    
    if ($isDigitalOcean) {
        // DigitalOcean Production Database Settings (not used - session storage enabled)
        $Server = "demo-server";
        $Username = "demo-user";
        $Password = "demo-password";
        $Database = "demo-database";
        $Port = 3306;
        
        error_log("Using DigitalOcean database configuration");
    } else {
        // Local Development Settings
        $Server = "localhost";
        $Username = "root";
        $Password = "";
        $Database = "PharmacyX_DB";
        $Port = 3306;
        
        error_log("Using local development configuration");
    }
}

// Session storage is always available, no complex connection needed
if ($useSessionStorage) {
    $Connection = true; // Session storage is always "connected"
    error_log("Session storage ready!");
} else {
    // Original database connection code (kept for reference)
    // Validate required parameters
    if (empty($Server) || empty($Username) || empty($Database)) {
        error_log("Missing required database parameters - Server: $Server, Username: $Username, Database: $Database");
        die("Database configuration error: Missing required connection parameters");
    }
    
    // Create connection with proper error handling
    try {
        // For DigitalOcean, we need SSL connection
        if ($isDigitalOcean) {
            // DigitalOcean connection with SSL
            $Connection = mysqli_init();
            
            if (!$Connection) {
                throw new Exception("mysqli_init failed");
            }
            
            // Set SSL options for DigitalOcean (required)
            mysqli_ssl_set($Connection, NULL, NULL, NULL, NULL, NULL);
            
            // Connect with SSL to DigitalOcean
            $connected = mysqli_real_connect(
                $Connection,
                $Server,
                $Username,
                $Password,
                $Database,
                $Port,
                NULL,
                MYSQLI_CLIENT_SSL
            );
            
            if (!$connected) {
                throw new Exception("DigitalOcean SSL connection failed: " . mysqli_connect_error());
            }
            
            error_log("DigitalOcean SSL connection successful!");
            
        } else {
            // Local development connection
            $Connection = mysqli_connect($Server, $Username, $Password, $Database, $Port);
            
            if (!$Connection) {
                throw new Exception("Local connection failed: " . mysqli_connect_error());
            }
        }
        
        // Set charset
        mysqli_set_charset($Connection, "utf8mb4");
        
        // Test the connection
        $testQuery = "SELECT 1";
        $testResult = mysqli_query($Connection, $testQuery);
        
        if (!$testResult) {
            throw new Exception("Database connection test failed: " . mysqli_error($Connection));
        }
        
        error_log("Database connection successful!");
        
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        error_log("Connection details - Host: $Server, Port: $Port, User: $Username, DB: $Database");
        
        // Show user-friendly error message
        if ($isDigitalOcean) {
            // Production - show generic error but log details
            die("Database connection error. Please check server logs for details. Error: " . $e->getMessage());
        } else {
            // Development - show detailed error
            die("Database connection failed: " . $e->getMessage());
        }
    }
}

// Legacy variable names for compatibility
$servername = $Server;
$username = $Username;
$password = $Password;
$database = $Database;
$conn = $Connection;

?>