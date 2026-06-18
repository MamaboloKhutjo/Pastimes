<?php
// backend/checkout_handler.php
session_start();
require_once __DIR__ . '/DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_method = $_POST['delivery_method'] ?? 'courier_guy';
    $promo_code = $_POST['promo_code'] ?? '';

    // Get cart items (simple version)
    $result = $conn->query("SELECT c.*, cl.price FROM tblcart c 
                           JOIN tblclothes cl ON c.clothing_id = cl.clothing_id 
                           WHERE c.user_id = $user_id");

    $total = 0;
    while ($item = $result->fetch_assoc()) {
        $total += $item['price'] * $item['quantity'];
    }

    $delivery_fee = ($delivery_method === 'courier_guy') ? 89 : 55;
    $final_total = $total + $delivery_fee;

    // Create order
    $stmt = $conn->prepare("INSERT INTO tblaorder (buyer_id, total_amount, delivery_method, delivery_fee, status) 
                           VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isdi", $user_id, $final_total, $delivery_method, $delivery_fee);
    
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;
        
        // Clear cart
        $conn->query("DELETE FROM tblcart WHERE user_id = $user_id");

        $_SESSION['success'] = "Order #$order_id placed successfully! Thank you.";
        header("Location: ../frontend/profile-buyer.php");
    } else {
        $_SESSION['error'] = "Checkout failed.";
        header("Location: ../frontend/cart.php");
    }
    exit();
}
?>