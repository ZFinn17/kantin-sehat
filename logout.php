<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Return success response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Logout berhasil'
]);
?>