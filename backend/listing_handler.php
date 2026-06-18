<?php
session_start();
require_once __DIR__ . '/DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_POST['action'] !== 'create_listing') {
    header("Location: ../frontend/home.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ---------------- INPUT ---------------- */

$title       = trim($_POST['title']);
$price       = (float)$_POST['price'];
$condition   = $_POST['condition'];
$size        = $_POST['size'];
$material    = $_POST['material'];
$city        = $_POST['city'];
$category    = $_POST['category'];
$description = $_POST['description'];

/* ---------------- IMAGE UPLOAD ---------------- */

$image_path = './assets/images/default.jpg';

if (!empty($_FILES['images']['name'][0])) {

    $folder = "../uploads/listings/";

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $fileName = time() . "_" . basename($_FILES['images']['name'][0]);
    $target = $folder . $fileName;

    if (move_uploaded_file($_FILES['images']['tmp_name'][0], $target)) {
        $image_path = "./uploads/listings/" . $fileName;
    }
}

/* ---------------- INSERT ---------------- */

$stmt = $conn->prepare("
    INSERT INTO tblclothes
    (seller_id, title, description, price, `condition`, size, material, category, city, images, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available')
");

$stmt->bind_param(
    "issdssssss",
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

$stmt->execute();

/* ---------------- REDIRECT ---------------- */

header("Location: ../frontend/home.php");
exit();