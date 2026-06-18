<?php
session_start();
require_once __DIR__ . '/DBConn.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$user_id = $_SESSION['user_id'];

switch ($action) {
    case 'add_to_cart':
        $clothing_id = (int)$_POST['clothing_id'];
        $quantity = (int)($_POST['quantity'] ?? 1);

        // Check if already in cart
        $check = $conn->prepare("SELECT id FROM tblcart WHERE user_id = ? AND clothing_id = ?");
        $check->bind_param("ii", $user_id, $clothing_id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE tblcart SET quantity = quantity + ? WHERE user_id = ? AND clothing_id = ?");
            $stmt->bind_param("iii", $quantity, $user_id, $clothing_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO tblcart (user_id, clothing_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $clothing_id, $quantity);
        }
        $success = $stmt->execute();
        echo json_encode(['success' => $success, 'message' => $success ? 'Added to cart' : 'Failed']);
        break;

    case 'remove_from_cart':
        $cart_id = (int)$_POST['cart_id'];
        $stmt = $conn->prepare("DELETE FROM tblcart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        echo json_encode(['success' => $stmt->execute()]);
        break;

    case 'update_quantity':
        $cart_id = (int)$_POST['cart_id'];
        $qty = (int)$_POST['quantity'];
        $stmt = $conn->prepare("UPDATE tblcart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $qty, $cart_id, $user_id);
        echo json_encode(['success' => $stmt->execute()]);
        break;

    case 'get_cart':
        $result = $conn->query("
            SELECT c.id as cart_id, c.quantity, cl.*, u.first_name, u.last_name 
            FROM tblcart c 
            JOIN tblclothes cl ON c.clothing_id = cl.clothing_id 
            JOIN tbluser u ON cl.seller_id = u.user_id 
            WHERE c.user_id = $user_id
        ");
        $items = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'items' => $items]);
        break;
}
?>