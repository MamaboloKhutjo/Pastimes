<?php

session_start();
require_once __DIR__ . '/DBConn.php';

// Security: Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../frontend/admin.php"); // Redirect back to admin dashboard
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'broadcast') {
    
    $audience = $_POST['audience'] ?? 'all';
    $message = trim($_POST['message'] ?? '');

    if (empty($message)) {
        $_SESSION['broadcast_error'] = "Please enter a broadcast message.";
        header("Location: ../frontend/admin.php");
        exit();
    }

    $admin_id = $_SESSION['admin_id'];

    $_SESSION['broadcast_success'] = "✅ Broadcast successfully sent to <strong>" . htmlspecialchars($audience) . "</strong> users!";

    // Log the broadcast (optional table - we'll create it later if needed)
    $stmt = $conn->prepare("INSERT INTO broadcast_logs (admin_id, audience, message, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("iss", $admin_id, $audience, $message);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../frontend/admin.php");
    exit();
}

// Fallback redirect
header("Location: ../frontend/admin.php");
exit();
?>