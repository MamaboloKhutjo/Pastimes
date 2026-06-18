<?php

session_start();
require_once __DIR__ . '/DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'add_to_cart':
        $clothing_id = (int)$_POST['clothing_id'];
        $quantity = (int)($_POST['quantity'] ?? 1);

        // Check if item already in cart
        $check = $conn->prepare("SELECT * FROM tblcart WHERE user_id = ? AND clothing_id = ?");
        $check->bind_param("ii", $user_id, $clothing_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $conn->query("UPDATE tblcart SET quantity = quantity + $quantity WHERE user_id = $user_id AND clothing_id = $clothing_id");
        } else {
            $stmt = $conn->prepare("INSERT INTO tblcart (user_id, clothing_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $clothing_id, $quantity);
            $stmt->execute();
        }

        $_SESSION['success'] = "Item added to cart!";
        header("Location: ../frontend/cart.php");
        exit();

    case 'remove_from_cart':
        $cart_id = (int)$_GET['id'];
        $conn->query("DELETE FROM tblcart WHERE cart_id = $cart_id AND user_id = $user_id");
        header("Location: ../frontend/cart.php");
        exit();

    case 'update_quantity':
        $cart_id = (int)$_POST['cart_id'];
        $qty = (int)$_POST['quantity'];
        if ($qty > 0) {
            $conn->query("UPDATE tblcart SET quantity = $qty WHERE cart_id = $cart_id AND user_id = $user_id");
        }
        header("Location: ../frontend/cart.php");
        exit();

    default:
        header("Location: ../frontend/cart.php");
        exit();
}
?>