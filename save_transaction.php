<?php
// save_transaction.php - FINAL FIXED VERSION
session_start();

// Debug mode ON
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Simple CORS for local development
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

// ============ DEBUG LOGGING ============
$log_file = __DIR__ . '/transaction_debug.log';
$log_message = "[" . date('Y-m-d H:i:s') . "] ===== NEW REQUEST =====\n";
$log_message .= "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
$log_message .= "Session ID: " . session_id() . "\n";
$log_message .= "Session Data: " . json_encode($_SESSION) . "\n";

// ============ CHECK ADMIN LOGIN ============
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $log_message .= "ERROR: Admin not logged in\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized: Admin login required',
        'session_status' => session_status(),
        'session_id' => session_id()
    ]);
    exit;
}

$log_message .= "Admin logged in: " . ($_SESSION['admin_username'] ?? 'unknown') . "\n";

// ============ GET POST DATA ============
$input = file_get_contents('php://input');
$log_message .= "Raw input length: " . strlen($input) . "\n";
$log_message .= "Raw input (first 500 chars): " . substr($input, 0, 500) . "\n";

if (empty($input)) {
    $log_message .= "ERROR: Empty input\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No data received'
    ]);
    exit;
}

$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $log_message .= "ERROR: Invalid JSON - " . json_last_error_msg() . "\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid JSON: ' . json_last_error_msg(),
        'input_sample' => substr($input, 0, 200)
    ]);
    exit;
}

$log_message .= "JSON decoded successfully\n";
$log_message .= "Data keys: " . implode(', ', array_keys($data)) . "\n";

// Validate required fields
if (!isset($data['items']) || !isset($data['total'])) {
    $log_message .= "ERROR: Missing required fields\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing items or total field'
    ]);
    exit;
}

// ============ DATABASE CONNECTION ============
try {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "kantin_bahagia";
    
    $log_message .= "Connecting to database: $database\n";
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    
    $log_message .= "Database connected successfully\n";
    
    // ============ PREPARE DATA ============
    // Ensure items is valid JSON
    $items_json = json_encode($data['items'], JSON_UNESCAPED_UNICODE);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Failed to encode items to JSON: " . json_last_error_msg());
    }
    
    $total = intval($data['total']);
    $admin_id = $_SESSION['admin_id'] ?? 0;
    $admin_username = $_SESSION['admin_username'] ?? 'unknown';
    $created_at = date('Y-m-d H:i:s');
    
    $log_message .= "Prepared data:\n";
    $log_message .= "- Items JSON length: " . strlen($items_json) . "\n";
    $log_message .= "- Total: $total\n";
    $log_message .= "- Admin ID: $admin_id\n";
    $log_message .= "- Admin Username: $admin_username\n";
    
    // ============ CHECK TABLE STRUCTURE ============
    // First, check if table exists and has correct columns
    $check_table = $conn->query("SHOW TABLES LIKE 'transactions'");
    if ($check_table->num_rows == 0) {
        $log_message .= "Table 'transactions' does not exist, creating...\n";
        
        $create_sql = "CREATE TABLE transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            items LONGTEXT NOT NULL,
            total INT NOT NULL,
            admin_id INT,
            admin_username VARCHAR(50),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        if (!$conn->query($create_sql)) {
            throw new Exception("Failed to create table: " . $conn->error);
        }
        
        $log_message .= "Table created successfully\n";
    } else {
        // Check if columns exist
        $check_columns = $conn->query("SHOW COLUMNS FROM transactions");
        $columns = [];
        while ($row = $check_columns->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        
        $log_message .= "Existing columns: " . implode(', ', $columns) . "\n";
        
        // Add missing columns if needed
        if (!in_array('admin_id', $columns)) {
            $conn->query("ALTER TABLE transactions ADD COLUMN admin_id INT AFTER total");
            $log_message .= "Added column: admin_id\n";
        }
        
        if (!in_array('admin_username', $columns)) {
            $conn->query("ALTER TABLE transactions ADD COLUMN admin_username VARCHAR(50) AFTER admin_id");
            $log_message .= "Added column: admin_username\n";
        }
    }
    
    // ============ INSERT TRANSACTION ============
    // Use simple query first to test
    $items_escaped = $conn->real_escape_string($items_json);
    
    $sql = "INSERT INTO transactions (items, total, admin_id, admin_username, created_at) 
            VALUES ('$items_escaped', $total, $admin_id, '$admin_username', '$created_at')";
    
    $log_message .= "SQL Query: $sql\n";
    
    if ($conn->query($sql)) {
        $transaction_id = $conn->insert_id;
        $log_message .= "SUCCESS: Transaction inserted! ID: $transaction_id\n";
        
        $response = [
            'success' => true,
            'message' => 'Transaksi berhasil disimpan!',
            'transaction_id' => $transaction_id,
            'admin' => $admin_username,
            'total' => $total,
            'items_count' => count($data['items'])
        ];
        
    } else {
        throw new Exception("Insert failed: " . $conn->error);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    $log_message .= "EXCEPTION: " . $e->getMessage() . "\n";
    $response = [
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage(),
        'code' => 500
    ];
    http_response_code(500);
}

// ============ FINAL LOG AND RESPONSE ============
$log_message .= "Response: " . json_encode($response) . "\n";
$log_message .= "=================================\n\n";
file_put_contents($log_file, $log_message, FILE_APPEND);

echo json_encode($response);
?>