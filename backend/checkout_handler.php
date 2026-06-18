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
   $cart_result = $conn->query("
        SELECT c.cart_id, c.quantity, cl.clothing_id, cl.price 
        FROM tblcart c 
        JOIN tblclothes cl ON c.clothing_id = cl.clothing_id 
        WHERE c.user_id = $user_id
    ");

    $total = 0;
    $order_items = [];

    while ($item = $cart_result->fetch_assoc()) {
        $total += $item['price'] * $item['quantity'];
        $order_items[] = $item;
    }

    if (empty($order_items)) {
        $_SESSION['error'] = "Your cart is empty.";
        header("Location: ../frontend/cart.php");
        exit();
    }

    $delivery_fee = ($delivery_method === 'courier_guy') ? 89 : 55;
    $final_total = $total + $delivery_fee;

    // Create Order
    $stmt = $conn->prepare("INSERT INTO tblaorder (buyer_id, total_amount, delivery_method, delivery_fee, status) 
                           VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isdi", $user_id, $final_total, $delivery_method, $delivery_fee);
    
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;

        // Insert Order Items
        foreach ($order_items as $item) {
            $item_stmt = $conn->prepare("INSERT INTO tblorderitem (order_id, clothing_id, quantity, price_at_time) 
                                       VALUES (?, ?, ?, ?)");
            $item_stmt->bind_param("iiid", $order_id, $item['clothing_id'], $item['quantity'], $item['price']);
            $item_stmt->execute();
        }

        // Clear Cart
        $conn->query("DELETE FROM tblcart WHERE user_id = $user_id");

        $_SESSION['success'] = "Order #$order_id placed successfully! Thank you for shopping with Pastimes.";
        header("Location: ../frontend/profile-buyer.php");
        exit();
    } else {
        $_SESSION['error'] = "Checkout failed. Please try again.";
        header("Location: ../frontend/cart.php");
        exit();
    }
}
?>