<?php

session_start();
require_once __DIR__ . '/DBConn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $price = (float)$_POST['price'];
    $condition = $_POST['condition'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $size = $_POST['size'] ?? '';
    $material = $_POST['material'] ?? '';
    $city = $_POST['city'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $tags = $_POST['tags'] ?? '';

    if (empty($title) || $price <= 0) {
        $_SESSION['error'] = "Title and price are required.";
        header("Location: ../frontend/new-listing.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO tblclothes 
        (seller_id, title, description, price, `condition`, size, material, category, city, tags, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available')");

    $stmt->bind_param("issdssssss", $user_id, $title, $description, $price, $condition, $size, $material, $category, $city, $tags);

    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        $_SESSION['success'] = "Listing published successfully! ID: #$new_id";
        header("Location: ../frontend/profile-seller.php");
    } else {
        $_SESSION['error'] = "Failed to create listing.";
        header("Location: ../frontend/new-listing.php");
    }
    exit();
}
?>