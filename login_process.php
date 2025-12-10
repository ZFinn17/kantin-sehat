<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "kantin_bahagia";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'error' => 'Database connection failed'
    ]));
}

// Get POST data
$input_username = $_POST['username'] ?? '';
$input_password = $_POST['password'] ?? '';

// Validate input
if (empty($input_username) || empty($input_password)) {
    echo json_encode([
        'success' => false,
        'error' => 'Username dan password harus diisi'
    ]);
    exit;
}

// Create admin table if not exists
$createTableSQL = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($createTableSQL)) {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to create admin table'
    ]);
    exit;
}

// Check if admin exists, if not create default admin
$checkAdminSQL = "SELECT COUNT(*) as count FROM admin";
$result = $conn->query($checkAdminSQL);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // Create default admin (username: admin, password: admin123)
    $default_username = 'admin';
    $default_password = password_hash('admin123', PASSWORD_DEFAULT);
    
    $insertSQL = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($insertSQL);
    $stmt->bind_param("ss", $default_username, $default_password);
    
    if (!$stmt->execute()) {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create default admin'
        ]);
        exit;
    }
}

// Check login credentials
$sql = "SELECT id, username, password FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $input_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    
    // Verify password (using password_hash for security)
    if (password_verify($input_password, $admin['password'])) {
        // Login successful
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil!',
            'username' => $admin['username']
        ]);
        
    } else {
        // Wrong password
        echo json_encode([
            'success' => false,
            'error' => 'Password salah'
        ]);
    }
    
} else {
    // User not found
    echo json_encode([
        'success' => false,
        'error' => 'Username tidak ditemukan'
    ]);
}

$stmt->close();
$conn->close();
?>