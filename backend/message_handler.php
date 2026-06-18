<?php
session_start();
require_once __DIR__ . '/DBConn.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'send_message') {
    $receiver_id = (int)$_POST['receiver_id'];
    $clothing_id = (int)($_POST['clothing_id'] ?? 0);
    $message = trim($_POST['message']);

    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO tblmessages (sender_id, receiver_id, clothing_id, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $receiver_id, $clothing_id, $message);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Message sent']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send']);
    }
}
?>