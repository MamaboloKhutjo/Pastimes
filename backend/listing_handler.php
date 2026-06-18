<?php
session_start();
require_once __DIR__ . '/DBConn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'create_listing') {

    $title       = trim($_POST['title'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $condition   = trim($_POST['condition'] ?? '');
    $size        = trim($_POST['size'] ?? '');
    $material    = trim($_POST['material'] ?? '');
    $city        = trim($_POST['city'] ?? '');
    $category    = trim($_POST['category'] ?? 'other');
    $description = trim($_POST['description'] ?? '');

    if (empty($title) || $price <= 0 || empty($condition)) {
        echo json_encode(['success' => false, 'message' => 'Title, price, and condition are required']);
        exit();
    }

    // Handle image upload (save path)
    $image_path = '';
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] === 0) {
        $upload_dir = '../uploads/listings/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = time() . '_' . basename($_FILES['images']['name'][0]);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['images']['tmp_name'][0], $target_file)) {
            $image_path = './uploads/listings/' . $file_name;
        }
    }

    // SQL matching your actual tblclothes table
    $stmt = $conn->prepare("
        INSERT INTO tblclothes 
        (seller_id, title, description, price, `condition`, size, material, category, city, images, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available', NOW())
    ");

    $stmt->bind_param("issdssssss", 
        $user_id, 
        $title, 
        $description, 
        $price, 
        $condition, 
        $size, 
        $material, 
        $category, 
        $city, 
        $image_path
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Listing published successfully!";
        header("Location: ../frontend/home.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to create listing: " . $conn->error;
        header("Location: ../frontend/new-listing.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../frontend/home.php");
    exit();
}
?>